<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaffModels\Staff;
use Config;

class MigratePasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:passwords {user?} {--D|default} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Passwords';

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


        $use_default_password = $this->option('default');

        $allStaff = Staff::All();
        $bar = $this->output->createProgressBar(count($allStaff));

        foreach ($allStaff as $key => $value) {
            $staff = Staff::find($value->id);
            if($use_default_password){
                $staff->password = bcrypt(Config::get('app.default_password'));
            }else{                
                $staff->password = bcrypt($this->removeInvalidCharacters($this->decrypt_password($staff->old_password)));
            }
            $staff->save();
            $bar->advance();
            // $this->info("\nUser:".$staff->email);
        }

        $this->info("Done!");
    }
    public function encrypt_password($password_str){
        $offset = 8;
        $encrypted_password = '';
        for ($i = 1; $i <= strlen($password_str); $i++) 
        {
            $encrypted_password.=chr((ord(substr($password_str,$i-1,1)) + $offset));
        }
        return $encrypted_password;
    }
    public function decrypt_password($password_str){
        $offset = 8;
        $decrypted_password = '';
        for ($i = 1; $i <= strlen($password_str); $i++) 
        {
            $decrypted_password.=chr((ord(substr($password_str,$i-1,1)) - $offset));
        }
        return $decrypted_password;
    }

    public function removeInvalidCharacters($string){
        ini_set('mbstring.substitute_character', "none"); 
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }
}
