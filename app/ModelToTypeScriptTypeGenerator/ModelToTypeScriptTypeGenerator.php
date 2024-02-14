<?php

namespace App\ModelToTypeScriptTypeGenerator;

use App\ModelToTypeScriptTypeGenerator\Nodes\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class ModelToTypeScriptTypeGenerator
{
    private string $outputDirectory;

    private string $modelDirectory;

    public function __construct()
    {
        $this->outputDirectory = config('type-script-generator.output_directory');
        $this->modelDirectory = config('type-script-generator.model_directory');
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

    /**
     * @return Collection<string>
     */
    private function getFullyQualifiedModelNames(): Collection
    {
        return collect(array_diff(scandir($this->modelDirectory), ['..', '.']))
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
