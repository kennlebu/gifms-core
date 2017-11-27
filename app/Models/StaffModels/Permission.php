<?php

namespace App\Models\StaffModels;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{


    public function approval_level()
    {
        return $this->belongsTo('App\Models\ApprovalsModels\ApprovalLevel','approval_level_id');
    }
}
