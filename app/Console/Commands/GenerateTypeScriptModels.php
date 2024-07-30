<?php

namespace App\Console\Commands;

use App\Services\TypeScriptModelGenerator\TypeScriptModelGenerator;
use Illuminate\Console\Command;

class GenerateTypeScriptModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-type-script-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate TypeScript types for Laravel models';

    /**
     * Execute the console command.
     *
     * @phpstan-ignore-next-line
     */
    public function handle()
    {
        (new TypeScriptModelGenerator)->generateAll();

        $this->line('TypeScript types generated.');
    }
}
