<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('users')->updateOrInsert(
            [
                'email' => env('ADMIN_EMAIL')
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => 'admin',
            ]);

        echo 'Admin created. Email: ' . env('ADMIN_EMAIL') . "\r\n";
        return 0;
    }
}
