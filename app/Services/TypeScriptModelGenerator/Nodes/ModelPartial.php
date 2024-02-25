<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;

class ModelPartial
{
    private Filesystem $files;

    private function __construct(
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
        static::validateConfig($config);

        return new self(
            name: Arr::get($config, 'name'),
            model: new (Arr::get($config, 'model')),
            fields: collect((array) Arr::get($config, 'fields'))
        );
    }

    private static function validateConfig(array $config): void
    {
        if (!Arr::has($config, ['name', 'model', 'fields'])) {
            throw new InvalidArgumentException('invalid config array');
        }

        if (!is_string(Arr::get($config, 'name'))) {
            throw new InvalidArgumentException('invalid config value ['.Arr::get($config, 'name').'] for the field [name]');
        }

        if (empty(Arr::get($config, 'name'))) {
            throw new InvalidArgumentException('config field [name] may not be empty');
        }

        if (!is_string(Arr::get($config, 'model'))) {
            throw new InvalidArgumentException('invalid config value ['.Arr::get($config, 'model').'] for the field [model]');
        }

        if (empty(Arr::get($config, 'model'))) {
            throw new InvalidArgumentException('config field [model] may not be empty');
        }

        try {
            new (Arr::get($config, 'model'));
        } catch (Error) {
            throw new InvalidArgumentException('config field [model] is not a valid model (class not found)');
        }

        if (!new (Arr::get($config, 'model')) instanceof Model) {
            throw new InvalidArgumentException('config field [model] is not a valid model (not a model class)');
        }

        if (!is_array(Arr::get($config, 'fields'))) {
            throw new InvalidArgumentException('invalid config value ['.Arr::get($config, 'fields').'] for the field [fields]');
        }

        if (empty(Arr::get($config, 'fields'))) {
            throw new InvalidArgumentException('config field [fields] may not be empty');
        }
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');
        $modelName = (new ReflectionClass($this->model))->getShortName();

        return Str::of($this->files->get(__DIR__.'/../stubs/ModelPartialType.stub'))
            ->replace('{{ model }}', $modelName)
            ->replace('{{ importPath }}', "{$importDirectory}/{$modelName}")
            ->replace('{{ name }}', $this->name)
            ->replace('{{ fields }}', $this->fields->map(fn (string $field) => "'{$field}'")->join(' | '));
    }
}
