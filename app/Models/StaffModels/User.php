<?php

namespace App\Models\StaffModels;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    protected $table = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'security_group',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    protected $appends = ['full_name','is_admin'];







    public function getFullNameAttribute()
    {       

        return $this->attributes['f_name'].' '.$this->attributes['l_name'];

    }

    public function getIsAdminAttribute()
    {       
        //this is a stub
        $is_admin = 0;

        
        return $is_admin;

    }
}
