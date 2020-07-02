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

    
    protected $appends = ['full_name','name'];







    public function getFullNameAttribute()
    {       

        return $this->attributes['f_name'].' '.$this->attributes['l_name'];

    }

    public function getNameAttribute()
    {       

        return $this->attributes['f_name'].' '.$this->attributes['l_name'];

    }
    
    public function roles()
    {
        return $this->belongsToMany('App\Models\StaffModels\Role','user_roles','user_id', 'role_id');
    }
    
    public function programs()
    {
        return $this->belongsToMany('App\Models\ProgramModels\Program','program_staffs','staff_id','program_id');
    }
    
    public function projects()
    {
        return $this->belongsToMany('App\Models\ProjectsModels\Project','project_teams','staff_id','project_id');
    }

}
