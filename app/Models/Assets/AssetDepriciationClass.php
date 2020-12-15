<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;

class AssetDepriciationClass extends BaseModel
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $appends = ['percentage_text'];

    public function getPercentageTextAttribute() {
        return $this->percentage.'%';
    }
}
