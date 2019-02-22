<?php

namespace App\Models\FinanceModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class BudgetItem extends BaseModel
{
    //
    use SoftDeletes;

    public function project()
    {
        return $this->belongsTo('App\Models\ProjectsModels\Project','project_id');
    }
    public function objective()
    {
        return $this->belongsTo('App\Models\ReportModels\ReportingObjective', 'objective_id');
    }
    
}
