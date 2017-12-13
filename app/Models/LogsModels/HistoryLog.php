<?php

namespace App\Models\LogsModels;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModels\BaseModel;


class HistoryLog extends Model
{
    //

    protected $table = 'activity_log';

    public function subject()
    {
        return $this->morphTo();
    }
    public function causer()
    {
        return $this->morphTo();
    }
}
