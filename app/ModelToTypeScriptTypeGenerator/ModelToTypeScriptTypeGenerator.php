<?php

namespace App\ModelToTypeScriptTypeGenerator;

use App\ModelToTypeScriptTypeGenerator\Nodes\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelToTypeScriptTypeGenerator
{
    private string $outputDirectory = './resources/js/types/generated/Models';

    private Model $model;

    public function __construct(string $fullyQualifiedModelName)
    {
        $this->model = new $fullyQualifiedModelName();
    }

    public function generate(): void
    {
        $modelName = Str::of($this->model::class)->split('/\\\\/')->last();

        $type = new Type($this->model);

        // TODO: consider model attributes
        // TODO: consider `$appends`

        if (!file_exists($this->outputDirectory)) {
            mkdir($this->outputDirectory, 0755, true);
        }

        file_put_contents("./resources/js/types/generated/Models/{$modelName}.ts", $type->toString());
    }
}
