<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BirthdayWish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:birthdaywish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $user   =   \App\Models\Patient::whereDate('birthdate','=',date('m-d'))->select('*')->all();
        return $user;
    }
}
