<?php

namespace App\Models\LogsModels;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModels\BaseModel;


class HistoryLog extends Model
{
    //

    protected $table = 'activity_log';

    public function subjectable()
    {
        return $this->morphTo();
    }
    public function causable()
    {
        return $this->morphTo();
    }
}
