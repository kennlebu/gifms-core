<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class SupplyCategory extends BaseModel
{
    use SoftDeletes;
    protected $appends = ['service_type_text'];
    
    public function service_types()
    {
        return $this->belongsToMany('App\Models\SuppliesModels\SupplierServiceType','supply_category_services', 'supply_category_id', 'supplier_service_type_id');
    }

    public function getServiceTypeTextAttribute() {
        $text = '';
        foreach($this->service_types as $st) {
            $text .= $st->display_value .', ';
        }
        
        if(empty($text)) {
            $text = '-';
        }
        else {
            $text = trim($text, ', \t\n\r\0\x0B');
        }
        
        return $text;
    }
}
