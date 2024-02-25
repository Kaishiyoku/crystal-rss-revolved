<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class Type
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

    private Filesystem $files;

    public function __construct(private readonly Model $model)
    {
        $this->files = new Filesystem();

        $this->name = (new ReflectionClass($this->model))->getShortName();

        $this->properties = collect([
            ...Arr::pluck(Schema::getColumns($this->model->getTable()), 'name'),
            ...$this->model->getAppends(),
        ])->map(fn (string $fieldName) => new TypeProperty($this->model, $fieldName));

        $this->relationshipProperties = collect((new ReflectionClass($this->model))->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter($this->filterRelationshipPropertyReturnType(...))
            ->mapWithKeys($this->mapRelationshipProperty(...));
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');

        $imports = $this->relationshipProperties
            ->map(function (string $relationshipName, string $fieldName) use ($importDirectory) {
                $adjustedRelationshipName = Str::replace('[]', '', $relationshipName);

                return "import {$adjustedRelationshipName} from '{$importDirectory}/{$adjustedRelationshipName}';";
            })
            ->join("\n");

        $propertiesStr = $this->properties
            ->map(fn (TypeProperty $property) => $property->toString())
            ->join("\n    ");

        $relationshipPropertiesStr = $this->relationshipProperties
            ->map(fn (string $relationshipName, string $fieldName) => "{$fieldName}: {$relationshipName};")
            ->join("\n    ");

        return Str::of($this->files->get(__DIR__.'/../stubs/Type.stub'))
            ->replace('{{ imports }}', $imports)
            ->replace('{{ name }}', $this->name)
            ->replace('{{ properties }}', $propertiesStr)
            ->replace('{{ relationshipProperties }}', $relationshipPropertiesStr);
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

        return [$reflectionMethod->getName() => $relationshipName];
    }

    private function filterRelationshipPropertyReturnType(ReflectionMethod $reflectionMethod): bool
    {
        return in_array($reflectionMethod->getReturnType(), [
            BelongsTo::class,
            HasMany::class,
            HasManyThrough::class,
        ]);
    }
}
