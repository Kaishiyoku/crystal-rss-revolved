<?php

namespace App\Console\Commands;

use App\Models\User;
use App\ModelToTypeScriptTypeGenerator\ModelToTypeScriptTypeGenerator;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class GenerateTypeScriptModelTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-type-script-model-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates TypeScript types for every Laravel model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        collect(array_diff(scandir('./app/Models'), ['..', '.']))
            ->map(fn (string $fileName) => Str::of($fileName)
                ->replaceEnd('.php', '')
                ->prepend('App\\Models\\')
                ->toString())
            ->each($this->modelMapper(...));
    }

    private function modelMapper(string $fullyQualifiedModelName): void
    {
        (new ModelToTypeScriptTypeGenerator($fullyQualifiedModelName))->generate();
    }
}
