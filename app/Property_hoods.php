<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Property_hoods extends Model
{
	protected $table = "property_hoods";

	protected $fillable = array('property_deal','hood','year','price');

	static function get_by_condition($table, $filters)
	{
		$data = $table;

		foreach($filters as $filter)
		{
			$data = $data->where($filter[0], $filter[1], $filter[2]);
		}
		$data->orderBy('year');
		$data = $data->get();

		if(count($data) > 0)
		{
			$data = $data->toArray();
			return $data;
		}
		else
		{
			return false;
		}

	}
	 
}
