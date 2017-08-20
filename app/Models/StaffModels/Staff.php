<?php

namespace App\Models\StaffModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Staff extends BaseModel
{
    
    use SoftDeletes;    
  	use Notifiable;

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



    protected $appends = ['full_name','name','is_admin'];







    public function getFullNameAttribute()
    {       

        return $this->attributes['f_name'].' '.$this->attributes['l_name'];

    }

    public function getNameAttribute()
    {       

        return $this->attributes['f_name'].' '.$this->attributes['l_name'];

    }

    public function getIsAdminAttribute()
    {       
        //this is a stub
        $is_admin = 0;

        
        return $is_admin;

    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\StaffModels\Role','user_roles','user_id', 'role_id');
    }
}