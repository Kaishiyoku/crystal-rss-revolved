<?php

namespace App\Console\Commands;

use App\ModelToTypeScriptTypeGenerator\ModelToTypeScriptTypeGenerator;
use Illuminate\Console\Command;

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
    public function handle()
    {
        (new ModelToTypeScriptTypeGenerator())->generateAll();

        $this->line('TypeScript types generated.');
    }
}
