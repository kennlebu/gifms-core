<?php

namespace App\Models\LogsModels;
use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{

    protected $table = 'activity_log';

    public function subject()
    {
        return $this->morphTo();
    }
    public function causer()
    {
        return $this->morphTo();
    }
    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }
}
