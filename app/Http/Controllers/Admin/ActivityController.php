<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use Illuminate\Http\Request;
use Auth;
use Input;
use Redirect;
use Excel;
use App\Offer;
use App\AdminUser;
use App\properties\PropertyModel as property;
use Crypt;
use App\Http\Controllers\CI_ModelController as common;
use App\User as UsersModel;
use App\LogesModel as activity;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\Response;

class ActivityController extends Controller
{

	protected  $data;
	public function __construct()
	{
		$this->middleware('auth.admin');
		$this->offer = new Offer();
		$this->objAdminUser = new AdminUser();
	}

	public function index()
	{
		$this->data['varifiedadmin'] = session("adminlog");

		if(Auth::guard('admin')->user()->role->type != 'admin') {
			return redirect('/admin/dashboard');
		}

		if($this->data['varifiedadmin']) {
			$logs = common::get_all(new activity(), -1, array("id" => "DESC"));

			if ($logs != false) {
				$data2 = array();
				foreach ($logs as $log) {
					$filter = array("id" => $log['userid']);
					$table = new UsersModel();
					$user = common::get_single($table, $filter);

					if ($user != false) {
						$log['userid'] = $user['name'] . " " . $user['lastname'];
					} else {
						$log['userid'] = "<b>ERROR - EXIST</b>";
					}
					$log['interval'] = "Unkown";
					if ($log['leave_time'] != '0000-00-00 00:00:00') {
						$log['interval'] = common::timetostr($log['arrive_time'], $log['leave_time']);
					}
					$data2[] = $log;
				}
				$logs = $data2;
			}

			$this->data['logs'] = $logs;
		}

		$this->view = "logs";
		return $this->load_view();
	}

	function load_view()
	{
		return view("admin.".$this->view, $this->data);
	}

	function authenticate(Request $request)
	{
		$password = md5($request->input("authentication"));
		$data = DB::table("authentications")
			->where("password", $password)
			->where("auth_for", "logs")
			->count();
		if($data > 0)
		{
			Session::flash("adminlog",true);
		}
		else{
			Session::flash("adminlog",false);
		}
		return redirect("/admin/logs");
	}

	function export($type = "")
	{
		$data = new activity();
		$data = $data->select(
			"loquare_logs.id",
			"loquare_logs.userid",
			"users.name",
			"users.lastname",
			"loquare_logs.page_url",
			"loquare_logs.arrive_time",
			"loquare_logs.leave_time",
			"loquare_logs.time_spend",
			"loquare_logs.log_type",
			"loquare_logs.log_message",
			"loquare_logs.ip_address",
			"loquare_logs.browser",
			"loquare_logs.session_id",
			"loquare_logs.created_at"
		);

		$data = $data->orderBy("id","DESC");
		$data = $data->join('users', 'users.id', '=', 'loquare_logs.userid');
		$totallogs = $data->count();
		$data = $data->get();

		if($totallogs > 0) {
			$filename = "Logs-" . date('YmdHis') . rand(0, 99);
			if($type == "xls")
			{

				Excel::create($filename, function ($excel) use ($data, $filename) {

					$excel->sheet('Exported Users', function (\PHPExcel_Worksheet $sheet) use ($data) {
						$sheet->with($data);
					});

					$excel->setTitle($filename);
				})->store($type, storage_path('Logs'));

				return Response::download(storage_path("Logs") . "/" . $filename.".".$type);

			}

			if($type == "csv")
			{
				$headers = array(
					"Content-type" => "text/csv",
					"Content-Disposition" => "attachment; filename=".$filename.".csv",
					"Pragma" => "no-cache",
					"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
					"Expires" => "0"
				);

				$columns = explode("\t","id	userid	name	lastname	page_url	arrive_time	leave_time	time_spend	log_type	log_message	ip_address	browser	session_id	created_at
");

				$callback = function() use ($data, $columns)
				{
					$file = fopen('php://output', 'w');
					fputcsv($file, $columns);

					foreach($data as $cols) {
						fputcsv($file, array($cols->id, $cols->userid, $cols->name, $cols->lastname, $cols->page_url, $cols->arrive_time, $cols->leave_time, $cols->time_spend, $cols->log_type, $cols->log_message, $cols->ip_address, $cols->browser, $cols->session_id, $cols->created_at));
					}
					fclose($file);
				};
				return Response::stream($callback, 200, $headers);
			}
		}
		else{
			return false;
		}

	}

}

?>