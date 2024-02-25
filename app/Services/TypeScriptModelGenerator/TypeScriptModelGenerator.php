<?php

namespace App\Services\TypeScriptModelGenerator;

use App\Services\TypeScriptModelGenerator\Nodes\InheritedType;
use App\Services\TypeScriptModelGenerator\Nodes\Partial;
use App\Services\TypeScriptModelGenerator\Nodes\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class TypeScriptModelGenerator
{
    private string $outputDirectory;

    private string $modelDirectory;

    public function __construct()
    {
        $this->outputDirectory = config('type-script-model-generator.output_directory');
        $this->modelDirectory = config('type-script-model-generator.model_directory');
    }

    public function generateAll(): void
    {
        $this->getFullyQualifiedModelNames()
            ->each($this->generateModel(...));
    }

    /**
     * @throws ReflectionException
     */
    public function generateModel(string $fullyQualifiedModelName): void
    {
        $this->generateModelType($fullyQualifiedModelName);
        $this->generateModelPartialTypes($fullyQualifiedModelName);
        $this->generateModelInheritedTypes($fullyQualifiedModelName);
    }

    private function generateModelType(string $fullyQualifiedModelName): void
    {
        $model = new $fullyQualifiedModelName();
        $modelName = (new ReflectionClass($model))->getShortName();

        $type = new Type($model);

        if (! file_exists($this->outputDirectory)) {
            mkdir($this->outputDirectory, 0755, true);
        }

        file_put_contents(
            filename: "{$this->outputDirectory}/{$modelName}.ts",
            data: $type->toString(),
        );
    }

    private function generateModelPartialTypes(string $fullyQualifiedModelName): void
    {
        collect((array) config('type-script-model-generator.partials'))
            ->filter(fn (array $config) => Arr::get($config, 'model') === $fullyQualifiedModelName)
            ->map(Partial::fromConfig(...))
            ->each(function (Partial $partial) {
                file_put_contents(
                    filename: "{$this->outputDirectory}/{$partial->name}.ts",
                    data: $partial->toString(),
                );
            });
    }

    private function generateModelInheritedTypes(string $fullyQualifiedModelName): void
    {
        collect((array) config('type-script-model-generator.inherited_types'))
            ->filter(fn (array $config) => Arr::get($config, 'model') === $fullyQualifiedModelName)
            ->map(InheritedType::fromConfig(...))
            ->each(function (InheritedType $inherited) {
                file_put_contents(
                    filename: "{$this->outputDirectory}/{$inherited->name}.ts",
                    data: $inherited->toString(),
                );
            });
    }

    /**
     * @return Collection<string>
     */
    private function getFullyQualifiedModelNames(): Collection
    {
        return collect(array_diff((array) scandir($this->modelDirectory), ['..', '.']))
            ->map(fn (string $fileName) => Str::of($fileName)
                ->replaceEnd('.php', '')
                ->prepend(Str::of($this->modelDirectory)
                    ->replaceFirst('./', '')
                    ->append('\\')
                    ->split('/\//')
                    ->map(Str::ucfirst(...))
                    ->join('\\')
                )
                ->toString()
            );
    }
}
