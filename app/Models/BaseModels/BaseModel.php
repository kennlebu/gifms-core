<?php

namespace App\Models\BaseModels;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

	// public function newPivot(Eloquent $parent, array $attributes, $table, $exists){
	//     return new BaseModel($parent, $attributes, $table, $exists);
	// }




	

	protected function arr_to_dt_response($data,$draw,$total_records,$records_filtered){

		foreach ($data as $key => $value) {
			$data[$key]['DT_RowId'] = 'row_'.$value['id'];
			$data[$key]['DT_RowData'] = array('pkey'=>$value['id']);
		}

		return array(
					'draw' 				=> 	$draw,
					'sEcho' 			=> 	$draw,
					'recordsTotal' 		=> 	$total_records,
					'recordsFiltered' 	=> 	$records_filtered,
					'data' 				=> 	$data
			);

	}

	protected function bind_presql($sql, $bindings){
		
		$needle = '?';

        foreach ($bindings as $replace){
            $pos = strpos($sql, $needle);
            if ($pos !== false) {
                $sql = substr_replace($sql, $replace, $pos, strlen($needle));
            }
        }
        return $sql;

	}

}
