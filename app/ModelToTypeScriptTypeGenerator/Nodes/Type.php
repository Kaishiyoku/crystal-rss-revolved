<?php

namespace App\ModelToTypeScriptTypeGenerator\Nodes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;

class Type
{
    public function __construct(
        private readonly string $name,

        /**
         * @var Collection<TypeProperty>
         */
        private readonly Collection $properties,
    ) {
    }

    public static function fromModel(Model $model): self
    {
        $properties = collect([
            ...Arr::pluck(Schema::getColumns($model->getTable()), 'name'),
            ...$model->getAppends(),
        ])->map(static fn (string $fieldName) => TypeProperty::fromModelField($model, $fieldName));

        return new self(
            name: (new ReflectionClass($model))->getShortName(),
            properties: $properties,
        );
    }

    public function toString(): string
    {
        $propertiesStr = $this->properties
            ->map(static fn (TypeProperty $property) => $property->toString())
            ->join("\n    ");

        return <<<TS
type {$this->name} = {
    {$propertiesStr}
}

export default {$this->name};

TS;

    }
}
