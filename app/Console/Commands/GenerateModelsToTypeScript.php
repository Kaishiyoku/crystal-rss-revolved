<?php

namespace App\Console\Commands;

use App\Services\TypeScriptModelGenerator\TypeScriptModelGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateModelsToTypeScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-models-to-ts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates TypeScript types for every Laravel model';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        (new TypeScriptModelGenerator())->generateAll();

        $this->line('TypeScript types generated.');

        return SymfonyCommand::SUCCESS;
    }
}
