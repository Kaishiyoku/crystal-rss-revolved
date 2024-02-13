<?php

namespace App\ModelToTypeScriptTypeGenerator\Nodes;

use App\ModelToTypeScriptTypeGenerator\Enums\ReturnType;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    public function __construct(
        public readonly string $name,

        /**
         * @var Collection<ReturnType>
         */
        public readonly Collection $returnTypes,

        public readonly ?string $comment = null,
    ) {
    }

    public static function fromModelField(Model $model, string $fieldName): self
    {
        $comment = null;

        $databaseColumnSchema = collect(Schema::getColumns($model->getTable()))
            ->filter(static fn (array $column) => Arr::get($column, 'name') === $fieldName)
            ->first();

        $modelAttributeReturnTypes = static::getModelAttributeReturnTypesForField($model, $fieldName);

        $returnTypes = static::mapDatabaseSchemaReturnTypes($databaseColumnSchema);
        if ($modelAttributeReturnTypes) {
            $returnTypes = $modelAttributeReturnTypes;
            $comment .= 'model attribute';
        }

        if (!$returnTypes) {
            $returnTypes = collect(ReturnType::Unknown);
            $comment .= 'no return types found';
        }

        return new self(
            name: $fieldName,
            returnTypes: $returnTypes,
            comment: $comment,
        );
    }

    /**
     * @return Collection<ReturnType>|null
     */
    private static function getModelAttributeReturnTypesForField(Model $model, string $fieldName): ?Collection
    {
        try {
            $attributeReflectionMethod = new ReflectionMethod($model, Str::camel($fieldName));
            /*** @var Attribute $attribute */
            $attribute = $attributeReflectionMethod->getClosure($model)->call($model);
            $attributeGetterReflectionProperty = new ReflectionProperty($attribute, 'get');
            $attributeGetterReflectionClosure = new ReflectionFunction($attributeGetterReflectionProperty->getValue($attribute));

            $reflectionReturnType = $attributeGetterReflectionClosure->getReturnType();

            if (!$reflectionReturnType) {
                return null;
            }

            return collect([
                static::mapCodeReturnTypes($reflectionReturnType->getName()),
                $reflectionReturnType->allowsNull() ? ReturnType::Null : null,
            ])->filter();
        } catch (ReflectionException $exception) {
            return null;
        }
    }

    /**
     * @return Collection<ReturnType>|null
     */
    private static function mapDatabaseSchemaReturnTypes(?array $databaseColumnSchema): ?Collection
    {
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

    private static function mapCodeReturnTypes(string $codeTypeName): ReturnType
    {
        return match ($codeTypeName) {
            'bool' => ReturnType::Boolean,
        };
    }

    public function toString(): string
    {
        $comment = $this->comment ? " /** {$this->comment} */" : '';

        return <<<TS
{$this->name}: {$this->returnTypes->map(static fn (ReturnType $returnType) => $returnType->value)->join(' | ')}{$comment};
TS;

    }
}
