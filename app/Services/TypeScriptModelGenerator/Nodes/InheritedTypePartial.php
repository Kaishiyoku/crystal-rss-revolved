<?php

namespace App\Services\TypeScriptModelGenerator\Nodes;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InheritedTypePartial
{
    private Filesystem $files;

    public function __construct(
        public string $name,

        private string $type,

        /**
         * @var Collection<string>
         */
        private Collection $fields,
    ) {
        $this->files = new Filesystem();
    }

    /**
     * @param  array{name: string, type: string, fields: string[]}  $config
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            name: Arr::get($config, 'name'),
            type: Arr::get($config, 'type'),
            fields: collect((array) Arr::get($config, 'fields'))
        );
    }

    public function toString(): string
    {
        $importDirectory = config('type-script-model-generator.import_directory');

        return Str::of($this->files->get(__DIR__.'/../stubs/InheritedTypePartialType.stub'))
            ->replace('{{ type }}', $this->type)
            ->replace('{{ importPath }}', "{$importDirectory}/{$this->type}")
            ->replace('{{ name }}', $this->name)
            ->replace('{{ fields }}', $this->fields->map(fn (string $field) => "'{$field}'")->join(' | '));
    }
}
