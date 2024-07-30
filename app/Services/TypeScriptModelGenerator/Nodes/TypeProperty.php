<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use App\Services\TypeScriptModelGenerator\Enums\ReturnType;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * @codeCoverageIgnore
 */
class TypeProperty implements Arrayable
{
    /**
     * @var Collection<ReturnType|ReflectionEnum>
     */
    private Collection $returnTypes;

    private ?string $comment = null;

    private Filesystem $files;

    public function __construct(
        private readonly Model $model,
        public readonly string $name,
    ) {
        $this->files = new Filesystem;

        $returnTypes = $this->getDatabaseSchemaReturnTypes();
        $modelAttributeReturnTypes = $this->getModelAttributeReturnTypes();
        $castReturnType = $this->getCastReturnType();

        if ($modelAttributeReturnTypes) {
            $returnTypes = $modelAttributeReturnTypes;
            $this->comment .= 'model attribute';
        }

        $isNullable = $returnTypes?->filter(fn (ReturnType $returnType) => $returnType === ReturnType::Null)->isNotEmpty();

        if ($castReturnType) {
            $returnTypes = collect([$castReturnType]);
            $this->comment .= 'cast attribute';

            if ($isNullable) {
                $returnTypes->add(ReturnType::Null);
            }
        }

        if (! $returnTypes) {
            $returnTypes = collect([ReturnType::Unknown]);
            $this->comment .= 'no return types found';
        }

        $this->returnTypes = $returnTypes;
    }

    /**
     * @param  array{model: string, name: string, types: string[]}  $config
     */
    public static function fromInheritedTypeConfig(array $config): self
    {
        $self = new self(
            model: new (Arr::get($config, 'model')),
            name: Arr::get($config, 'name'),
        );

        $configReturnTypes = collect((array) Arr::get($config, 'types'))
            ->map(ReturnType::from(...));

        $self->returnTypes = $configReturnTypes->isEmpty() ? $self->returnTypes : $configReturnTypes;
        $self->comment = $configReturnTypes->isEmpty() ? $self->comment : '';

        return $self;
    }

    public function toString(?string $customReturnType = null): string
    {
        $returnTypesStr = $customReturnType ?? $this->returnTypes
            ->map(fn (ReturnType|ReflectionEnum $returnType) => $returnType instanceof ReturnType ? $returnType->value : $returnType->getShortName())
            ->join(' | ');

        return Str::of($this->files->get(__DIR__.'/../stubs/TypeProperty.stub'))
            ->replace('{{ name }}', $this->name)
            ->replace('{{ returnTypes }}', $returnTypesStr)
            ->replace('{{ comment }}', $this->comment ? " /** {$this->comment} */" : '')
            ->replaceLast("\n", '');
    }

    public function toArray()
    {
        return [
            'returnTypes' => $this->returnTypes,
            'comment' => $this->comment,
            'model' => (new ReflectionClass($this->model))->getShortName(),
            'name' => $this->name,
        ];
    }

    /**
     * @return Collection<ReturnType|ReflectionEnum>
     */
    public function getReturnTypes(): Collection
    {
        return $this->returnTypes;
    }

    /**
     * @return Collection<ReturnType>|null
     */
    private function getModelAttributeReturnTypes(): ?Collection
    {
        try {
            $attributeReflectionMethod = new ReflectionMethod($this->model, Str::camel($this->name));
            $attribute = $attributeReflectionMethod->getClosure($this->model)->call($this->model);
            $attributeGetterReflectionProperty = new ReflectionProperty($attribute, 'get');
            $attributeGetterReflectionClosure = new ReflectionFunction($attributeGetterReflectionProperty->getValue($attribute));

            $reflectionReturnType = $attributeGetterReflectionClosure->getReturnType();

            if (! $reflectionReturnType instanceof ReflectionNamedType) {
                return null;
            }

            return collect([
                $this->mapCodeReturnTypes($reflectionReturnType->getName()),
                $reflectionReturnType->allowsNull() ? ReturnType::Null : null,
            ])->filter();
        } catch (ReflectionException) {
            return null;
        }
    }

    private function getCastReturnType(): ReturnType|ReflectionEnum|null
    {
        $castType = collect($this->model->getCasts())
            ->filter(fn (string $castType, string $fieldName) => $fieldName === $this->name)
            ->first();

        if (! $castType) {
            return null;
        }

        if (enum_exists($castType) && (new ReflectionEnum($castType))->getBackingType()) {
            return new ReflectionEnum($castType);
        }

        return match ($castType) {
            'int' => ReturnType::Number,
            'bool' => ReturnType::Boolean,
            'datetime', 'date', 'hashed' => ReturnType::String,
            default => throw new InvalidArgumentException("cast return type \"{$castType}\"not matched"),
        };
    }

    /**
     * @return Collection<ReturnType>|null
     */
    private function getDatabaseSchemaReturnTypes(): ?Collection
    {
        $databaseColumnSchema = collect(Schema::getColumns($this->model->getTable()))
            ->filter(fn (array $column) => Arr::get($column, 'name') === $this->name)
            ->first();

        if (! $databaseColumnSchema) {
            return null;
        }

        $databaseTypeName = Arr::get($databaseColumnSchema, 'type_name');

        $returnTypes = collect([match ($databaseTypeName) {
            'integer', 'int', 'double', 'bigint', 'smallint', 'tinyint' => ReturnType::Number,
            'varchar', 'timestamp', 'datetime', 'date', 'time', 'text' => ReturnType::String,
            'enum' => ReturnType::Enum,
            default => throw new InvalidArgumentException("database schema return type \"{$databaseTypeName}\"not matched"),
        }]);

        if (Arr::get($databaseColumnSchema, 'nullable')) {
            $returnTypes->push(ReturnType::Null);
        }

        return $returnTypes;
    }

    private function mapCodeReturnTypes(string $codeTypeName): ReturnType
    {
        return match ($codeTypeName) {
            'bool' => ReturnType::Boolean,
            'int', 'float' => ReturnType::Number,
            'string', Carbon::class => ReturnType::String,
            default => throw new InvalidArgumentException("code return type \"{$codeTypeName}\"not matched"),
        };
    }
}
