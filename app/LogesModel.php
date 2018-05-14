<?php

namespace App;

use App\Http\Controllers\LogsController;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\CI_ModelController as common;

class LogesModel extends Model
{
    protected  $table = "loquare_logs";

	protected  $fillable = array(
		'userid',
		'page_url',
		'arrive_time',
		'leave_time',
		'time_spend',
		'log_type',
		'log_message',
		'ip_address',
		'browser',
		'session_id',
		'created_at',
		'updated_at'
	);

	static function getuserlogs($user = "", $date = "")
	{
		if($user != "")
		{

			$filter = array(array("userid", "=", $user));

			if($date != ""){ $filter[] = array("arrive_time", "LIKE", "%".$date."%");}

			$table = new LogesModel();

			$data = common::get_by_condition($table, $filter);

			return $data;
		}
		else {
			return false;
		}
	}
}
