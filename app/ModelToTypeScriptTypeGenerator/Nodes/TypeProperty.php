<?php

namespace App\ModelToTypeScriptTypeGenerator\Nodes;

use App\ModelToTypeScriptTypeGenerator\Enums\ReturnType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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

    public function __construct(
        private readonly Model $model,
        public readonly string $name,
    ) {
        $returnTypes = $this->getDatabaseSchemaReturnTypes();
        $modelAttributeReturnTypes = $this->getModelAttributeReturnTypes();

        if ($modelAttributeReturnTypes) {
            $returnTypes = $modelAttributeReturnTypes;
            $this->comment .= 'model attribute';
        }

        if (!$returnTypes) {
            $returnTypes = collect(ReturnType::Unknown);
            $this->comment .= 'no return types found';
        }

        $this->returnTypes = $returnTypes;
    }

    public function toString(): string
    {
        $comment = $this->comment ? " /** {$this->comment} */" : '';

        return <<<TS
{$this->name}: {$this->returnTypes->map(fn (ReturnType $returnType) => $returnType->value)->join(' | ')}{$comment};
TS;

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

            if (!$reflectionReturnType) {
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
    private function getDatabaseSchemaReturnTypes(): ?Collection
    {
        $databaseColumnSchema = collect(Schema::getColumns($this->model->getTable()))
            ->filter(fn (array $column) => Arr::get($column, 'name') === $this->name)
            ->first();

        if (!$databaseColumnSchema) {
            return null;
        }

        $returnTypes = collect(match (Arr::get($databaseColumnSchema, 'type_name')) {
            'bigint', 'tinyint' => ReturnType::Number,
            'varchar', 'timestamp', 'datetime' => ReturnType::String,
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
        };
    }
}
