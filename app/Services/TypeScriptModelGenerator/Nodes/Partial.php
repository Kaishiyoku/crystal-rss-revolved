<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class Partial
{
    private Filesystem $files;

    public function __construct(
        public string $name,

        private Model $model,

        /**
         * @var Collection<string>
         */
        private Collection $fields,
    ) {
        $this->files = new Filesystem();
    }

    /**
     * @param  array{name: string, model: string, fields: string[]}  $config
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            name: Arr::get($config, 'name'),
            model: new (Arr::get($config, 'model')),
            fields: collect((array) Arr::get($config, 'fields'))
        );
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');
        $modelName = (new ReflectionClass($this->model))->getShortName();

        return Str::of($this->files->get(__DIR__.'/../stubs/PartialType.stub'))
            ->replace('{{ model }}', $modelName)
            ->replace('{{ importPath }}', "{$importDirectory}/{$modelName}")
            ->replace('{{ name }}', $this->name)
            ->replace('{{ fields }}', $this->fields->map(fn (string $field) => "'{$field}'")->join(' | '));
    }
}
