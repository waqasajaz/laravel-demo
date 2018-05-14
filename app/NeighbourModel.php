<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class NeighbourModel extends Model
{
	protected $table = "hoods";

	protected $fillable = array('dist_id', 'hood','image','status');

	protected function get_hoods($id = "")
	{
		if($id != "")
		{
			$data = DB::table($this->table)->where("dist_id", "=", $id);
			$data=$data->get();
			$data = json_decode(json_encode($data), true);
			return $data;
		}
		else{
			return false;
		}
	}

}
