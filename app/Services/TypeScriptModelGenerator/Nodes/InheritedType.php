<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use App\Services\TypeScriptModelGenerator\Enums\ReturnType;
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

/**
 * @codeCoverageIgnore
 */
class InheritedType
{
    private Filesystem $files;

    private function __construct(
        public readonly string $name,

        private readonly Model $model,

        /*** @var Collection<TypeProperty> */
        private readonly Collection $additionalFields,
    ) {
        $this->files = new Filesystem;
    }

    /**
     * @param  array{name: string, model: string, additional_fields: array{name: string, types: string[]}}  $config
     */
    public static function fromConfig(array $config): self
    {
        self::validateConfig($config);

        return new self(
            name: Arr::get($config, 'name'),
            model: new (Arr::get($config, 'model')),
            additionalFields: collect((array) Arr::get($config, 'additional_fields'))
                ->map(fn (array $additionalFieldConfig) => array_merge($additionalFieldConfig, Arr::only($config, 'model')))
                ->map(TypeProperty::fromInheritedTypeConfig(...))
        );
    }

    /**
     * @param  array{name: string, model: string, additional_fields: array{name: string, types: string[]}}  $config
     */
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
            'additional_fields' => ['required', 'array', 'filled'],
            'additional_fields.*.name' => ['required', 'string', 'filled'],
            'additional_fields.*.types' => [
                'required',
                'array',
                'filled',
                Rule::in(collect(ReturnType::cases())->map(fn (ReturnType $returnType) => $returnType->value)),
            ],
        ])->validate();

        try {
            new (Arr::get($config, 'model'));
        } catch (Error) {
            throw new InvalidArgumentException('config field [model] is not a valid model (class not found)');
        }

        if (! new (Arr::get($config, 'model')) instanceof Model) {
            throw new InvalidArgumentException('config field [model] is not a valid model (not a model class)');
        }
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');
        $modelName = (new ReflectionClass($this->model))->getShortName();

        return Str::of($this->files->get(__DIR__.'/../stubs/InheritedType.stub'))
            ->replace('{{ model }}', $modelName)
            ->replace('{{ importPath }}', "{$importDirectory}/{$modelName}")
            ->replace('{{ name }}', $this->name)
            ->replace('{{ fields }}', $this->additionalFields->map(fn (TypeProperty $typeProperty) => $typeProperty->toString())->join("\n    "));
    }
}
