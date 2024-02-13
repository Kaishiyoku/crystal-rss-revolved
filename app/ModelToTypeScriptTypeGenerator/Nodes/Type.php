<?php

namespace App\ModelToTypeScriptTypeGenerator\Nodes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;

class Type
{
    private string $name;

    /**
     * @var Collection<TypeProperty>
     */
    private Collection $properties;

    public function __construct(Model $model) {
        $this->name = (new ReflectionClass($model))->getShortName();

        $this->properties = collect([
            ...Arr::pluck(Schema::getColumns($model->getTable()), 'name'),
            ...$model->getAppends(),
        ])->map(fn (string $fieldName) => new TypeProperty($model, $fieldName));
    }

    public function toString(): string
    {
        $propertiesStr = $this->properties
            ->map(fn (TypeProperty $property) => $property->toString())
            ->join("\n    ");

        return <<<TS
type {$this->name} = {
    {$propertiesStr}
}

export default {$this->name};

TS;

    }
}
