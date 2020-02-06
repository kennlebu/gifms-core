<?php

namespace App\Models\Assets;

use App\Models\BaseModels\BaseModel;
use App\Models\StaffModels\Staff;
use App\Models\SuppliesModels\Supplier;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends BaseModel
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
    protected static $logOnlyDirty = true;
    protected $appends = ['assignee'];

    public function type()
    {
        return $this->belongsTo('App\Models\Assets\AssetType', 'type_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Assets\AssetStatus', 'status_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier', 'supplier_id');
    }

    public function donation_to()
    {
        return $this->belongsTo('App\Models\SuppliesModels\Supplier', 'donation_to_id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Assets\AssetClass', 'class_id');
    }

    public function insurance_type()
    {
        return $this->belongsTo('App\Models\Assets\AssetInsuranceType', 'insurance_type_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Assets\AssetLocation', 'location_id');
    }

    public function asset_name()
    {
        return $this->belongsTo('App\Models\Assets\AssetName', 'asset_name_id');
    }

    public function assigned_to()
    {
        // if(!empty($this->assignee_type) && $this->assignee_type == 'individual')
            return $this->belongsTo('App\Models\StaffModels\Staff', 'assigned_to_id');
        // else
        //     return $this->belongsTo('App\Models\SuppliesModels\Supplier', 'assigned_to_id');
        // // else
        // //     return $this->belongsTo('App\Models\StaffModels\Staff', 'assigned_to_id');
    }

    public function staff_responsible()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'staff_responsible_id');
    }

    public function program_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'program_manager_id');
    }

    public function added_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'added_by_id');
    }

    public function last_updater()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff', 'last_updated_by');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Assets\AssetGroup', 'asset_group_id');
    }
    public function logs()
    {
        return $this->morphMany('App\Models\LogsModels\HistoryLog', 'subject')->orderBy('created_at','asc');
    }
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo', 'lpo_id');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoicesModels\Invoice', 'invoice_id');
    }
    public function donation_batch()
    {
        return $this->belongsTo('App\Models\Assets\AssetTransferBatch', 'donation_batch_id');
    }
    public function transfers()
    {
        return $this->hasMany('App\Models\Assets\AssetTransfer', 'batch_id');
    }
    public function loss()
    {
        return $this->hasOne('App\Models\Assets\AssetLoss', 'asset_id');
    }
    public function delivery_item()
    {
        return $this->belongsTo('App\Models\DeliveriesModels\DeliveryItem', 'delivery_item_id');
    }

    public function getAssigneeAttribute(){
        $assignee = null;
        if($this->assignee_type == 'individual'){
            $assignee = Staff::where($this->assigned_to_id);
        }
        elseif($this->assignee_type == 'care_partner' || $this->assignee_type == 'government'){
            $assignee = Supplier::find($this->assigned_to_id);
        }
        return $assignee;
    }
}
