<?php

namespace App\ModelToTypeScriptTypeGenerator;

use App\ModelToTypeScriptTypeGenerator\Nodes\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class ModelToTypeScriptTypeGenerator
{
    private string $outputDirectory = './resources/js/types/generated/Models';

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
            filename: "./resources/js/types/generated/Models/{$modelName}.ts",
            data: $type->toString(),
        );
    }

    /**
     * @return Collection<string>
     */
    private function getFullyQualifiedModelNames(): Collection
    {
        return collect(array_diff(scandir('./app/Models'), ['..', '.']))
            ->map(fn (string $fileName) => Str::of($fileName)
                ->replaceEnd('.php', '')
                ->prepend('App\\Models\\')
                ->toString()
            );
    }
}
