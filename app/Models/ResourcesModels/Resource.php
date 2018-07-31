<?php

namespace App\Models\ResourcesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Resource extends BaseModel
{
    
    use SoftDeletes;    
  	use Notifiable;

      public function added_by()
      {
          return $this->belongsTo('App\Models\StaffModels\Staff','staff_id');
      }
}