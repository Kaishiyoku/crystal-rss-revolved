<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use App\Services\TypeScriptModelGenerator\Enums\ReturnType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

class TypeProperty
{
    /**
     * @var Collection<ReturnType>
     */
    private Collection $returnTypes;

    private ?string $comment = null;

    private Filesystem $files;

    public function __construct(
        private readonly Model $model,
        public readonly string $name,
    ) {
        $this->files = new Filesystem();

        $returnTypes = $this->getDatabaseSchemaReturnTypes();
        $modelAttributeReturnTypes = $this->getModelAttributeReturnTypes();
        $castReturnTypes = $this->getCastReturnTypes();

        if ($modelAttributeReturnTypes) {
            $returnTypes = $modelAttributeReturnTypes;
            $this->comment .= 'model attribute';
        }

        if ($castReturnTypes) {
            $returnTypes = $castReturnTypes;
            $this->comment .= 'cast attribute';
        }

        if (! $returnTypes) {
            $returnTypes = collect(ReturnType::Unknown);
            $this->comment .= 'no return types found';
        }

        $this->returnTypes = $returnTypes;
    }

    public static function fromConfig(array $config): self
    {
        $self = new self(
            model: new (Arr::get($config, 'model')),
            name: Arr::get($config, 'name'),
        );

        $configReturnTypes = collect(Arr::get($config, 'types'))
            ->map(ReturnType::from(...));

        $self->returnTypes = $configReturnTypes->isEmpty() ? $self->returnTypes : $configReturnTypes;
        $self->comment = $configReturnTypes->isEmpty() ? $self->comment : '';

        return $self;
    }

    public function toString(): string
    {
        return Str::of($this->files->get(__DIR__.'/../stubs/TypeProperty.stub'))
            ->replace('{{ name }}', $this->name)
            ->replace('{{ returnTypes }}', $this->returnTypes->map(fn (ReturnType $returnType) => $returnType->value)->join(' | '))
            ->replace('{{ comment }}', $this->comment ? " /** {$this->comment} */" : '')
            ->replaceLast("\n", '');
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

            if (! $reflectionReturnType) {
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

    /**
     * @return Collection<ReturnType>|null
     */
    private function getCastReturnTypes(): ?Collection
    {
        $castType = collect($this->model->getCasts())
            ->filter(fn (string $castType, string $fieldName) => $fieldName === $this->name)
            ->first();

        if (! $castType) {
            return null;
        }

        return collect(match ($castType) {
            'int' => ReturnType::Number,
            'bool' => ReturnType::Boolean,
            'datetime' => ReturnType::String,
            default => throw new InvalidArgumentException("cast return type \"{$castType}\"not matched"),
        });
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

        $returnTypes = collect(match ($databaseTypeName) {
            'bigint', 'tinyint' => ReturnType::Number,
            'varchar', 'timestamp', 'datetime' => ReturnType::String,
            default => throw new InvalidArgumentException("database schema return type \"{$databaseTypeName}\"not matched"),
        });

        if (Arr::get($databaseColumnSchema, 'nullable')) {
            $returnTypes->push(ReturnType::Null);
        }

        return $returnTypes;
    }

    private function mapCodeReturnTypes(string $codeTypeName): ReturnType
    {
        return match ($codeTypeName) {
            'bool' => ReturnType::Boolean,
            default => throw new InvalidArgumentException("code return type \"{$codeTypeName}\"not matched"),
        };
    }
}
