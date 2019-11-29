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
        'password', 'remember_token','old_password', 'created_at', 'updated_at', 'deleted_at',
        'migration_bank_id', 'migration_bank_branch_id', 'migration_department_id', 'migration_id',
        'is_admin','receive_global_email_bcc','receives_global_bccs','security_group_id','station',];



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

    public function programs()
    {
        return $this->belongsToMany('App\Models\ProgramModels\Program','program_staffs','staff_id','program_id');
    }
    
    
    public function assigned_projects()
    {
        return $this->belongsToMany('App\Models\ProjectsModels\Project','project_teams','staff_id','project_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\StaffModels\Department','department_id');
    }
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentMode','payment_mode_id');
    }
    public function bank()
    {
        return $this->belongsTo('App\Models\BankingModels\Bank','bank_id');
    }
    public function bank_branch()
    {
        return $this->belongsTo('App\Models\BankingModels\BankBranch','bank_branch_id');
    }
    public function getMobileNoAttribute($value)
    {
        $no = preg_replace("/[^0-9]/", "", $value);
        // if(strlen($no)==9 && substr($no,0,1)=='7'){

        // }
        if(strlen($no)==12 && substr($no,0,3)=='254'){
            $no = substr($no,3);
        }
        elseif(strlen($no)==10 && substr($no,0,2)=='07'){
            $no = substr($no,1);
        }

        return $no;
    }
    public function getMpesaNoAttribute($value)
    {
        $no = preg_replace("/[^0-9]/", "", $value);
        // if(strlen($no)==9 && substr($no,0,1)=='7'){

        // }
        if(strlen($no)==12 && substr($no,0,3)=='254'){
            $no = substr($no,3);
        }
        elseif(strlen($no)==10 && substr($no,0,2)=='07'){
            $no = substr($no,1);
        }

        return $no;
    }
}