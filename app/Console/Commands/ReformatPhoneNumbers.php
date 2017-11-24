<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReformatPhoneNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reformat:phonenumbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reformats all system phone numbers';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
