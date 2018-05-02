<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Supplier extends BaseModel
{
    //
    use SoftDeletes;


    public function bank()
    {
        return $this->belongsTo('App\Models\BankingModels\Bank','bank_id');
    }
    public function bank_branch()
    {
        return $this->belongsTo('App\Models\BankingModels\BankBranch','bank_branch_id');
    }
    public function payment_mode()
    {
        return $this->belongsTo('App\Models\PaymentModels\PaymentMode','payment_mode_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
    public function county()
    {
        return $this->belongsTo('App\Models\LookupModels\County','county_id');
    }
    public function supply_category()
    {
        return $this->belongsTo('App\Models\SuppliesModels\SupplyCategory','supply_category_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','staff_id');
    }
}
