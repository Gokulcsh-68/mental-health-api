<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Entities\ApiAccess;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ApiAccessCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'auth:api-access';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:api-access {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Api access';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $apiAccess = ApiAccess::create([
            'username' => snake_case($this->argument('username')),
            'token' => bin2hex(openssl_random_pseudo_bytes(16)),
            'expiry_date' => Carbon::now()->addYear(10),
            'active' => true
        ]);

        $this->info($apiAccess->token);
    }
}