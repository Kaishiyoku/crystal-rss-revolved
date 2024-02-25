<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class InheritedType
{
    private Filesystem $files;

    public function __construct(
        public string $name,

        private readonly Model $model,

        /**
         * @var Collection<TypeProperty>
         */
        private readonly Collection $additionalFields,
    ) {
        $this->files = new Filesystem();
    }

    /**
     * @param  array{name: string, model: string, additional_fields: string | string[]}  $config
     * @return self
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            name: Arr::get($config, 'name'),
            model: new (Arr::get($config, 'model')),
            additionalFields: collect((array) Arr::get($config, 'additional_fields'))
                ->map(fn (array $additionalFieldConfig) => array_merge($additionalFieldConfig, Arr::only($config, 'model')))
                ->map(TypeProperty::fromInheritedTypeConfig(...))
        );
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');
        $modelName = (new ReflectionClass($this->model))->getShortName();

        return Str::of($this->files->get(__DIR__.'/../stubs/InheritedType.stub'))
            ->replace('{{ model }}', $modelName)
            ->replace('{{ importPath }}', "{$importDirectory}/{$modelName}")
            ->replace('{{ name }}', $this->name)
            ->replace('{{ fields }}', $this->additionalFields->map(fn (TypeProperty $typeProperty) => $typeProperty->toString())->join("\n"));
    }
}
