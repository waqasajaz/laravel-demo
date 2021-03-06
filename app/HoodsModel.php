<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class HoodsModel extends Model
{
	protected $table = "hoods";

	protected $fillable = array('dist_id','zip_code' ,'hood','image','coord_x','coord_y','area_code','shape','description');

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

	public function district()
	{
		return $this->belongsTo('App\DistrictModel', 'dist_id');
	}
}
