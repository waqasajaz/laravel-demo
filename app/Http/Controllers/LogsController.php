<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route;
use Illuminate\Http\Request as req;
use Request;
use Illuminate\Support\Facades\Auth;
use User;
use Session;
use App\Http\Controllers\CI_ModelController as common;
use DB;
use App\LogesModel as logs;
use Illuminate\Support\Facades\URL;
use App\PropertyVisitedModel as visited;
use App\properties\PropertyModel as property;


class LogsController extends Controller
{
	protected $data, $table;
    public function __construct()
    {
    	parent::__construct();
	    $this->table = "loquare_logs";
    }

    function index()
    {

    }

    static function addlog($activity = "")
    {
    	$processer = FALSE;

    	if($activity != "" && $activity['type'] != "login-admin")
	    {
		    $table = new logs();
		    $user = Auth::user();

		    if(!isset($user['id'])){ $user['id'] = $activity['userid']; }

		    $log['userid']       = $user['id'];
		    $log['ip_address']   = Request::ip();
		    $log['browser_type'] = Request::server('HTTP_USER_AGENT');
		    $log['created_at']   = date('Y-m-d H:i:s');
		    $log['session_id']   = session('sesid');
		    $log['leave_time']   = date('0000-00-00 00:00:00');

		    $update['userid']       = $user['id'];
		    $update['ip_address']   = Request::ip();
		    $update['browser']      = Request::userAgent();
		    $update['session_id']   = session('sesid');
		    $update['leave_time']   = date('Y-m-d H:i:s');

		    $filter = array(
			    "userid" => $user['id'],
			    "session_id" => session('sesid'),
		    );

		    $qry = "SELECT MAX(id) as lasturl FROM loquare_logs WHERE 
					`userid` = ".$user['id']." AND 
					`session_id` = '".session('sesid')."' AND 
					`log_type` != 'login' AND 
					(`leave_time` IS NULL OR `leave_time` = '0000-00-00 00:00:00')";

		    $last = DB::select($qry);
		    $last= (array)$last[0];

		    $last = common::get_single($table, array("id" => $last['lasturl']));

		    if($activity['type'] == "login")
		    {
		    	$filter['log_type'] = "login";
			    $data = common::get_single($table, $filter);

			    if($data != false)
			    {
				    $timespend = common::dateDifference($data['arrive_time'], $update['leave_time'], '%d,%h,%i,%s');
				    $timespend = common::getseconds($timespend);
				    $update['time_spend'] = $timespend;

			    	$flag = common::update_data($table, $update, $filter);

				    if($flag != false)
				    { $processer = TRUE; }

				    if($last['id'] != "" && $last['id'] != NULL) {
					    $lstid = array("id" => $last['id']);

					    $timespend = common::dateDifference($last['arrive_time'], $update['leave_time'], '%d,%h,%i,%s');
					    $timespend = common::getseconds($timespend);
					    $update['time_spend'] = $timespend;

					    $flag = common::update_data($table, $update, $lstid);

					    if($flag != false)
					    { $processer = TRUE; }
				    }
			    }
			    else{
				    $log['page_url']     = URL::current();
				    $log['log_type']     = $activity['type'];
				    $log['log_message']  = $activity['message'];
				    $log['arrive_time']  = date('Y-m-d H:i:s');
					$log['browser']      = Request::userAgent();
				    $log['ip_address']   = Request::ip();

				    $flag = common::insert_data($table, $log);

				    if($flag != false)
				    { $processer = TRUE; }
			    }
		    }
		    else{
			    $log['page_url']     = URL::current();
			    $log['log_type']     = $activity['type'];
			    $log['log_message']  = $activity['message'];
			    $log['arrive_time']  = date('Y-m-d H:i:s');
			    $log['browser']      = Request::userAgent();
			    $log['ip_address']   = Request::ip();
			    $flag = common::insert_data($table, $log);

			    if($activity['type'] == "realestate-visit-property" || $activity['type'] == "retailer-visit-property")
			    {
			    	$data = array(
			    		"userid"        => $user['id'],
			    		"property_id"   => $activity['property_id'],
			    		"property_from" => $activity['property_from'],
			    		"log_id"        => $flag,
			    		"created_at"    => date('Y-m-d H:i:s'),
					    "Updated_at"    => '0000-00-00 00:00:00'
				    );

				    common::insert_data(new visited(), $data);
			    }

			    if($last['id'] != "" && $last['id'] != NULL) {
				    $lstid = array("id" => $last['id']);

					$timespend = common::dateDifference($last['arrive_time'], $update['leave_time'], '%d,%h,%i,%s');
				    $timespend = common::getseconds($timespend);
				    $update['time_spend'] = $timespend;

				    $flag = common::update_data($table, $update, $lstid);
				    if($flag != false)
				    { $processer = TRUE; }
			    }
		    }

	    }

	    if($activity != "" && $activity['type'] == "login-admin")
	    {
		    $table = new logs();

		    $admin = Auth::guard('admin')->user();

		    $filter = array(
			    "userid" => $admin->id,
			    "session_id" => session('sesid'),
		    );

		    $qry = "SELECT MAX(id) as lasturl FROM loquare_logs WHERE 
					`userid` = ".$admin->id." AND 
					`session_id` = '".session('sesid')."' AND 
					`log_type` = 'login-admin' AND 
					(`leave_time` IS NULL OR `leave_time` = '0000-00-00 00:00:00')";

		    $last = DB::select($qry);
		    $last= (array)$last[0];

		    $last = common::get_single($table, array("id" => $last['lasturl']));

		    if($last != false)
		    {
				$update = array( "leave_time" => date('Y-m-d H:i:s') );

			    $timespend = common::dateDifference($last['arrive_time'], $update['leave_time'], '%d,%h,%i,%s');
			    $timespend = common::getseconds($timespend);
			    $update['time_spend'] = $timespend;
			    common::update_data($table, $update, array("id" => $last['id']));

		    }else{
			    $log['userid']       = $admin->id;
			    $log['page_url']     = URL::current();
			    $log['log_type']     = $activity['type'];
			    $log['log_message']  = $activity['message'];
			    $log['ip_address']   = Request::ip();
			    $log['browser']      = Request::userAgent();
			    $log['created_at']   = date('Y-m-d H:i:s');
			    $log['session_id']   = session('sesid');
			    $log['arrive_time']  = date('Y-m-d H:i:s');
			    $log['leave_time']   = date('0000-00-00 00:00:00');

			    common::insert_data($table, $log);
		    }


	    }

	    $processer = TRUE;
	    return $processer;
    }

    static  function closelog($logtype = "")
    {

	    $table = new logs();
	    $user = Auth::user();

	    $filter = array(
	    	"userid" => $user['id'],
		    "log_type" => $logtype,
		    "session_id" => session('sesid')
	    );
		$date = date('y-m-d H:i:s');

	    $last = common::get_single($table, $filter, array("id" => "DESC"));

	    if($last != false)
	    {
		    $timespend = common::dateDifference($last['arrive_time'], $date, '%d,%h,%i,%s');
		    $timespend = common::getseconds($timespend);

	    	$data = array(
				"leave_time" => date('Y-m-d H:i:s'),
			    "time_spend" => $timespend
		    );

		    $flag = common::update_data($table, $data, array("id" => $last['id']));
			if($flag != false)
			{
				echo json_encode(TRUE);
			}
			else
			{
				echo json_encode(FALSE);
			}
	    }
	    else{
	    	echo json_encode(FALSE);
	    }

	    echo json_encode(TRUE);
    }

    static function logcreate($start, $leave)
    {
	    $myfile = fopen("/var/www/html/loquare/public/logs.txt", "a") or die("Unable to open file!");

	    $txt = $start." : ".$leave."\n";
	    fwrite($myfile, $txt);

	    fclose($myfile);
    }

    function morgatelog()
    {
    	$price = Request::input("price");
	    $interest = Request::input("interest");
	    $year = Request::input("year");
	    $total = Request::input("total");
	    $percentage = Request::input("percentage");

		$property = Request::input("property");

		$property = common::get_single(new property(), array("id" => $property));

	    if($property != false)
	    {
	    	$message = "Calculate mortage for property ".$property['direccion'].", ".$property['cops']." ".$property['provincia']." - ";
		    $message .= " Price : ".$price.", Percentage : ".$percentage.", Interest : ".$interest.", Year : ".$year.", Result : ".$total;

		    $log = array(
		    	"type" => "mortage",
			    "message" => $message
		    );
		    LogsController::addlog($log);
	    }

	    echo json_encode(TRUE);
	    exit();
    }

    function tablogs()
    {
    	$tab = Request::input("tab");
	    $property = Request::input("property");

	    $property = common::get_single(new property(), array("id" => $property));

	    if($property != false)
	    {
			$message = "Open tab ".$tab ." for property ".$property['direccion'].", ".$property['cops']." ".$property['provincia'];
			$log = array(
				"type" => "open-tab",
		        "message" => $message
			);
		    LogsController::addlog($log);
	    }

	    echo json_encode(TRUE);
    }

}