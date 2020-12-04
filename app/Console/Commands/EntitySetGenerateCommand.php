<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

class EntitySetGenerateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:entitypkg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create entity package set';

    /**
     * Execute the console command.
     *
     * @return void
     */


    public function handle()
    {
        $name =  $this->argument('name');
        Artisan::call('make:entity', ['name' => $name]);
        Artisan::call('make:request', ['name' => $name]);
        Artisan::call('make:resource', ['name' => $name]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
