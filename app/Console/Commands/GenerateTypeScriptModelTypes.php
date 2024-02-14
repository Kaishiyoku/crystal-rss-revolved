<?php

namespace App\Console\Commands;

use App\ModelToTypeScriptTypeGenerator\ModelToTypeScriptTypeGenerator;
use Illuminate\Console\Command;

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
        (new ModelToTypeScriptTypeGenerator())->generateAll();
    }
}
