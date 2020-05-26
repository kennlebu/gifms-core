<?php

namespace App\Models\SuppliesModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo;

class Supplier extends BaseModel
{
    //
    use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at',
                        'migration_bank_branch_code', 'migration_bank_branch_id', 'migration_bank_id',
                        'migration_id', 'migration_staff_id'];


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
    public function documents()
    {
        return $this->hasMany('App\Models\SuppliesModels\SupplierDocument');
    }

    public function getTransactionsAttribute(){
        $transacations = [];
        $lpos = Lpo::with('requested_by','requisitioned_by','project_manager','status','invoices','currency','deliveries')
                    ->orderBy('id', 'desc')->get()->where('preferred_supplier_id', $this->id)->toArray();
        $invoices = Invoice::with('raised_by','project_manager','status')->where('supplier_id', $this->id)->orderBy('id', 'desc')->get();
        $transacations[] = ['type'=>'lpos', 'entries'=>array_values($lpos), 'name'=>'LPOs/LSOs'];
        $transacations[] = ['type'=>'invoices', 'entries'=>$invoices, 'name'=>'Invoices'];
        return $transacations;
    }

    public function getDocumentsAttribute(){
        $documents = [];
        return $documents;
    }
}
