<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use App\Services\TypeScriptModelGenerator\Enums\ReturnType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionEnum;
use ReflectionEnumBackedCase;
use ReflectionEnumUnitCase;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * @codeCoverageIgnore
 */
class Type implements Arrayable
{
    private string $name;

    /**
     * @var Collection<TypeProperty>
     */
    private Collection $properties;

    /**
     * @var Collection<string>
     */
    private Collection $relationshipProperties;

    /**
     * @var Collection<string, array<string>>
     */
    private Collection $morphToRelationshipProperties;

    private Filesystem $files;

    /**
     * @param  Collection<string>  $otherFullyQualifiedModelNames
     */
    public function __construct(
        private readonly Model $model,
        private readonly Collection $otherFullyQualifiedModelNames
    ) {
        $this->files = new Filesystem();

        $this->name = (new ReflectionClass($this->model))->getShortName();

        $this->properties = collect([
            ...Arr::pluck(Schema::getColumns($this->model->getTable()), 'name'),
            ...$this->model->getAppends(),
        ])->map(fn (string $fieldName) => new TypeProperty($this->model, $fieldName));

        $this->relationshipProperties = collect((new ReflectionClass($this->model))->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter($this->filterCommonRelationshipPropertyReturnType(...))
            ->mapWithKeys($this->mapRelationshipProperty(...));

        $this->morphToRelationshipProperties = collect((new ReflectionClass($this->model))->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter($this->filterMorphToRelationshipPropertyReturnType(...))
            ->mapWithKeys($this->mapMorphToRelationship(...));
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');

        $morphEnumNames = $this->morphToRelationshipProperties
            ->mapWithKeys(fn (array $relationshipNames, string $fieldName) => ["{$fieldName}_type" => Str::ucfirst($fieldName).'Type']);

        $castEnums = '';
        $imports = $this->relationshipProperties
            ->merge($this->morphToRelationshipProperties)
            ->map(function (string|array $relationshipNames, string $fieldName) {
                return collect(is_array($relationshipNames) ? $relationshipNames : [$relationshipNames])
                    ->map(function (string $relationshipName) {
                        $adjustedRelationshipName = Str::of($relationshipName)
                            ->replace(['[]', ' | null'], '')
                            ->split('/\\\\/')
                            ->last();

                        return $adjustedRelationshipName;
                    });
            })
            ->flatten()
            ->unique()
            ->filter(fn (string $relationshipName) => $relationshipName !== $this->name)
            ->map(fn (string $relationshipName) => "import {$relationshipName} from '{$importDirectory}/{$relationshipName}';")
            ->join("\n");

        $enumProperties = $this->properties
            ->map(fn (TypeProperty $property) => $property->getReturnTypes())
            ->flatten()
            ->filter(fn (ReturnType|ReflectionEnum $returnType) => $returnType instanceof ReflectionEnum);

        $castEnums = $enumProperties
            ->map(fn (ReflectionEnum $reflectionEnum) => Str::of($this->files->get(__DIR__.'/../stubs/Enum.stub'))
                ->replace('{{ name }}', $reflectionEnum->getShortName())
                ->replace('{{ values }}', collect($reflectionEnum->getCases())
                    ->filter(fn (ReflectionEnumUnitCase|ReflectionEnumBackedCase $reflectionEnumCase) => $reflectionEnumCase instanceof ReflectionEnumBackedCase)
                    ->map(function (ReflectionEnumBackedCase $reflectionEnumCase) {
                        if ($reflectionEnumCase->getEnum()->getBackingType()->getName() === 'int') {
                            return "{$reflectionEnumCase->getName()} = {$reflectionEnumCase->getBackingValue()},";
                        }

                        return "{$reflectionEnumCase->getName()} = '{$reflectionEnumCase->getBackingValue()}',";
                    })
                    ->join("\n    ")
                )
            )
            ->join("\n");

        $propertiesStr = $this->properties
            ->map(fn (TypeProperty $property) => $property->toString($morphEnumNames->get($property->name)))
            ->join("\n    ");

        $relationshipPropertiesStr = $this->relationshipProperties
            ->map(fn (string $relationshipName, string $fieldName) => "{$fieldName}: {$relationshipName};")
            ->join("\n    ");

        $morphToRelationshipPropertiesStr = $this->morphToRelationshipProperties
            ->map(function (array $relationshipNames, string $fieldName) {
                $relationshipNameStr = collect($relationshipNames)
                    ->map(fn (string $relationshipName) => Str::of($relationshipName)->split('/\\\\/')->last())
                    ->join(' | ');

                return "{$fieldName}: {$relationshipNameStr};";
            })
            ->join("\n    ");

        $morphToEnums = $this->morphToRelationshipProperties
            ->map(function (array $relationshipNames, string $fieldName) {
                return Str::of($this->files->get(__DIR__.'/../stubs/Enum.stub'))
                    ->replace('{{ name }}', Str::ucfirst($fieldName).'Type')
                    ->replace('{{ values }}', collect($relationshipNames)
                        ->map(fn (string $relationshipName) => Str::of($relationshipName)->split('/\\\\/')->last()." = '".Str::replace('\\', '\\\\', $relationshipName)."',")
                        ->join("\n    ")
                    );
            })
            ->join("\n");

        return Str::of($this->files->get(__DIR__.'/../stubs/Type.stub'))
            ->replace('{{ imports }}'.(! $imports ? "\n" : ''), $imports)
            ->replace("{{ castEnums }}\n".(! $castEnums ? "\n" : ''), $castEnums)
            ->replace("{{ morphToEnums }}\n".(! $morphToEnums ? "\n" : ''), $morphToEnums)
            ->replace('{{ name }}', $this->name)
            ->replace('{{ properties }}', $propertiesStr)
            ->replace('{{ relationshipProperties }}'.(! $relationshipPropertiesStr ? "\n    " : ''), $relationshipPropertiesStr)
            ->replace('{{ morphToRelationshipProperties }}'.(! $morphToRelationshipPropertiesStr ? "\n" : ''), $morphToRelationshipPropertiesStr)
            ->replace('    };', '};');
    }

    /**
     * @codeCoverageIgnore
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'properties' => $this->properties,
            'relationshipProperties' => $this->relationshipProperties,
            'model' => (new ReflectionClass($this->model))->getShortName(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function mapRelationshipProperty(ReflectionMethod $reflectionMethod): array
    {
        /*** @var Relation $relation */
        $relation = $reflectionMethod->getClosure($this->model)->call($this->model);

        $relationshipName = (new ReflectionClass($relation->getRelated()))->getShortName();

        if (in_array($reflectionMethod->getReturnType(), [HasMany::class, HasManyThrough::class])) {
            $relationshipName .= '[]';
        }

        // @codeCoverageIgnoreStart
        if (collect($reflectionMethod->getAttributes())
            ->filter(fn (ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->getName() === OptionalRelationship::class)
            ->isNotEmpty()
        ) {
            $relationshipName .= ' | '.ReturnType::Null->value;
        }
        // @codeCoverageIgnoreEnd

        return [Str::snake($reflectionMethod->getName()) => $relationshipName];
    }

    /**
     * @return array<string, string[]>
     */
    private function mapMorphToRelationship(ReflectionMethod $reflectionMethod): array
    {
        $returnTypes = $this->otherFullyQualifiedModelNames->map(function (string $fullyQualifiedModelName) use (
            $reflectionMethod
        ) {
            return collect((new ReflectionClass($fullyQualifiedModelName))->getMethods(ReflectionMethod::IS_PUBLIC))
                ->filter(fn (ReflectionMethod $reflectionMethod) => $reflectionMethod->getReturnType() instanceof ReflectionNamedType && $reflectionMethod->getReturnType()->getName() === MorphOne::class)
                ->map(function (ReflectionMethod $reflectionMethod) use ($fullyQualifiedModelName) {
                    /*** @var MorphOne $morphOneMethod */
                    $morphOneMethod = $reflectionMethod->invoke(new $fullyQualifiedModelName);

                    return [Str::replaceLast('_type', '', $morphOneMethod->getMorphType()), $fullyQualifiedModelName];
                })
                ->filter(function (array $morphRelationshipInfo) use ($reflectionMethod) {
                    [$morphName] = $morphRelationshipInfo;

                    return $morphName === $reflectionMethod->getName();
                })
                ->map(function (array $morphRelationshipsInfo) {
                    [, $fullyQualifiedModelName] = $morphRelationshipsInfo;

                    return $fullyQualifiedModelName;
                });
        })->flatten();

        return [$reflectionMethod->getName() => $returnTypes->toArray()];
    }

    private function filterCommonRelationshipPropertyReturnType(ReflectionMethod $reflectionMethod): bool
    {
        return in_array($reflectionMethod->getReturnType(), [
            BelongsTo::class,
            HasMany::class,
            HasManyThrough::class,
        ]);
    }

    private function filterMorphToRelationshipPropertyReturnType(ReflectionMethod $reflectionMethod): bool
    {
        return $reflectionMethod->getReturnType() instanceof ReflectionNamedType && $reflectionMethod->getReturnType()->getName() === MorphTo::class;
    }
}
