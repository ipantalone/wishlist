<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class KeyGenerateCommand
 * @package App\Console\Commands
 */
class GetRandomAuthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get a random token for auth";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = DB::table('users')->where('id_user', '=', DB::raw("abs(random()) % (SELECT max(id_user) FROM users)"))
            ->first();

        $this->info("Token: $key->token");
    }
}