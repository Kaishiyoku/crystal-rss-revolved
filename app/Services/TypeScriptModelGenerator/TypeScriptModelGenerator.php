<?php

namespace App\Services\TypeScriptModelGenerator;

use App\Services\TypeScriptModelGenerator\Nodes\InheritedType;
use App\Services\TypeScriptModelGenerator\Nodes\InheritedTypePartial;
use App\Services\TypeScriptModelGenerator\Nodes\ModelPartial;
use App\Services\TypeScriptModelGenerator\Nodes\Type;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class TypeScriptModelGenerator
{
    private string $outputDirectory;

    private string $modelDirectory;

    private Filesystem $files;

    public function __construct()
    {
        $this->outputDirectory = config('type-script-model-generator.output_directory');
        $this->modelDirectory = config('type-script-model-generator.model_directory');

        $this->files = new Filesystem;
    }

    public function generateAll(): void
    {
        $this->getFullyQualifiedModelNames()
            ->each($this->generateModel(...));

        $this->generateInheritedTypePartialTypes();
    }

    /**
     * @throws ReflectionException
     */
    public function generateModel(string $fullyQualifiedModelName): void
    {
        $otherFullyQualifiedModelNames = $this->getFullyQualifiedModelNames()
            ->filter(fn (string $name) => $name !== $fullyQualifiedModelName);

        $this->generateModelType($fullyQualifiedModelName, $otherFullyQualifiedModelNames);
        $this->generateModelPartialTypes($fullyQualifiedModelName);
        $this->generateModelInheritedTypes($fullyQualifiedModelName);
    }

    /**
     * @param  Collection<string>  $otherFullyQualifiedModelNames
     *
     * @throws ReflectionException
     */
    private function generateModelType(string $fullyQualifiedModelName, Collection $otherFullyQualifiedModelNames): void
    {
        $model = new $fullyQualifiedModelName;
        $modelName = (new ReflectionClass($model))->getShortName();

        $type = new Type($model, $otherFullyQualifiedModelNames);

        // @codeCoverageIgnoreStart
        if (! file_exists($this->outputDirectory)) {
            $this->files->makeDirectory($this->outputDirectory, 0755, true);
        }
        // @codeCoverageIgnoreEnd

        $this->files->put(
            path: "{$this->outputDirectory}/{$modelName}.ts",
            contents: $type->toString(),
        );
    }

    // @codeCoverageIgnoreStart
    private function generateModelPartialTypes(string $fullyQualifiedModelName): void
    {
        collect((array) config('type-script-model-generator.model_partials'))
            ->filter(fn (array $config) => Arr::get($config, 'model') === $fullyQualifiedModelName)
            ->map(ModelPartial::fromConfig(...))
            ->each(function (ModelPartial $modelPartial) {
                $this->files->put(
                    path: "{$this->outputDirectory}/{$modelPartial->name}.ts",
                    contents: $modelPartial->toString(),
                );
            });
    }
    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    private function generateModelInheritedTypes(string $fullyQualifiedModelName): void
    {
        collect((array) config('type-script-model-generator.inherited_types'))
            ->filter(fn (array $config) => Arr::get($config, 'model') === $fullyQualifiedModelName)
            ->map(InheritedType::fromConfig(...))
            ->each(function (InheritedType $inheritedType) {
                $this->files->put(
                    path: "{$this->outputDirectory}/{$inheritedType->name}.ts",
                    contents: $inheritedType->toString(),
                );
            });
    }
    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    private function generateInheritedTypePartialTypes(): void
    {
        collect((array) config('type-script-model-generator.inherited_type_partials'))
            ->map(InheritedTypePartial::fromConfig(...))
            ->each(function (InheritedTypePartial $typePartial) {
                $this->files->put(
                    path: "{$this->outputDirectory}/{$typePartial->name}.ts",
                    contents: $typePartial->toString(),
                );
            });
    }
    // @codeCoverageIgnoreEnd

    /**
     * @return Collection<string>
     */
    private function getFullyQualifiedModelNames(): Collection
    {
        return collect(array_diff((array) scandir($this->modelDirectory), ['..', '.']))
            ->filter(fn (string $name) => Str::endsWith($name, '.php'))
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
            )
            ->filter(fn (string $fullyQualifiedModelName) => ! in_array($fullyQualifiedModelName, config('type-script-model-generator.ignored_models')));
    }
}
