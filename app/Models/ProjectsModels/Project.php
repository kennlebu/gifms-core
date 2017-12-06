<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use Illuminate\Support\Facades\DB;

class Project extends BaseModel
{
    //
    use SoftDeletes;


    protected $appends = ['grant_amount_allocated','total_expenditure','expenditure_by_accounts_data'];

    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function project_manager()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','project_manager_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ProjectsModels\ProjectStatus','status_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\LookupModels\Country','country_id');
    }
    public function staffs()
    {
        return $this->belongsToMany('App\Models\StaffModels\Staff', 'project_teams', 'project_id', 'staff_id');
    }
    public function budget()
    {
        return $this->belongsTo('App\Models\FinanceModels\Budget', 'budget_id');
    }
    public function grant_allocations()
    {
        return $this->hasMany('App\Models\GrantModels\GrantAllocation','project_id');
    }
    public function allocations()
    {
        return $this->hasMany('App\Models\AllocationModels\Allocation','project_id');
    }
    // public function getAmountAllocatedAttribute(){

    //     $grant_allocations     =   $this->grant_allocations;
    //     $totals    =   0;

    //     foreach ($grant_allocations as $key => $value) {
    //         $totals    +=  (float) $value->amount_allocated;
    //     }

    //     return $totals;

    // }
    public function getGrantAmountAllocatedAttribute(){

        $grant_allocations     =   $this->grant_allocations;
        $totals    =   0;

        foreach ($grant_allocations as $key => $value) {
            $totals    +=  (float) $value->amount_allocated;
        }

        return $totals;

    }
    public function getTotalExpenditureAttribute(){

        $allocations     =   $this->allocations;
        $totals    =   0;

        foreach ($allocations as $key => $value) {
            $totals    +=  (float) $value->amount_allocated;
        }

        return $totals;

    }
    public function getTotalExpenditurePercAttribute(){
        $grant_amount_allocated     = (int)  $this->getGrantAmountAllocatedAttribute();
        $total_expenditure          = (int)  $this->getTotalExpenditureAttribute();

        if($grant_amount_allocated!=0){
            return ($total_expenditure/$grant_amount_allocated)*100;
        }else{
            return 0;
        }

    }

    public function getExpenditureByAccountsDataAttribute(){

        $project_id = $this->id;

        $qb = DB::table('allocations');

        $qb->select(DB::raw('accounts.account_name AS name, SUM(allocations.amount_allocated) AS `value`'))
                 ->leftJoin('projects', 'projects.id', '=', 'allocations.project_id')
                 ->leftJoin('accounts', 'accounts.id', '=', 'allocations.account_id')
                 ->where('projects.id', '=', $project_id)
                 ->whereNotNull('accounts.account_name')
                 ->groupBy('accounts.id')
                 ->orderBy('accounts.account_name', 'asc');



        $sql = $this->bind_presql($qb->toSql(),$qb->getBindings());
        $response = DB::select($sql);

        return $response;

    }
}
