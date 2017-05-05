<?php

namespace App\Models\AccountingModels;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

	public function newPivot(Model $parent, array $attributes, $table, $exists){
	    return new BaseModel($parent, $attributes, $table, $exists);
	}

}
