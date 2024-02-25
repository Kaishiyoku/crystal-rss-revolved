<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

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
        $modelName = '';

        try {
            $modelName = (new ReflectionClass(Arr::get($config, 'model')))->getShortName();
        } catch (ReflectionException) {
            // doesn't matter here
        }

        Validator::make($config, [
            'name' => ['required', 'string', 'filled', Rule::notIn($modelName)],
            'model' => ['required', 'string', 'filled'],
            'fields' => ['required', 'array', 'filled'],
            'fields.*' => ['required', 'string', 'filled'],
        ])->validate();

        try {
            new (Arr::get($config, 'model'));
        } catch (Error) {
            throw new InvalidArgumentException('config field [model] is not a valid model (class not found)');
        }

        if (!new (Arr::get($config, 'model')) instanceof Model) {
            throw new InvalidArgumentException('config field [model] is not a valid model (not a model class)');
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
