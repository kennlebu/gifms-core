<?php

namespace App\Models\StaffModels;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{


    public function permissions()
    {
        return $this->belongsToMany('App\Models\StaffModels\Permission');
    }
}
