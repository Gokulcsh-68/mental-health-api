<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ResourceMakeCommand::class,
        \App\Console\Commands\EntityMakeCommand::class,
        \App\Console\Commands\RequestMakeCommand::class,
        \App\Console\Commands\EntitySetGenerateCommand::class,
        \App\Console\Commands\IndexMigrationCommand::class,
        \App\Console\Commands\ApiAccessCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
