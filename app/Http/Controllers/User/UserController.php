<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\collection\CollectionModel as collect;
use App\Http\Controllers\CI_ModelController as common;
use App\properties\PropertyModel as property;
use Session;
use DB;
use Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Offer;
use App\Http\Controllers\LogsController as activity;
use Carbon\Carbon;
use App\UploadModel as uploadfile;
use Loquare;

class UserController extends Controller
{
	protected $data, $view, $logedin;

	public function __construct()
	{
		session::regenerate();

		$this->middleware('auth');
		$this->data['title'] = "Loquare";
		$this->data['layout'] = "template";
		$this->data['page'] = "Loquare";
		$this->data['success'] = session('success');
		$this->data['error'] = session('error');
		$this->data['location'] = "";
		$this->data['scripts'] = false;

		$this->middleware(function ($request, $next) {
			$this->logedin = Auth::user();
			return $next($request);
		});

	}

	public function load_view()
	{
		return view("user.".$this->view, $this->data);
	}

	function my_property($name = "")
	{
		if($name != "")
		{
			if($name == $this->logedin->name)
			{
				$this->data['scripts'] = array(
					asset('/frontend/js/my-property.js')
				);

				$log = array(
					"type" => "page",
					"message" => "Visited My Property page"
				);
				activity::addlog($log);

				$this->view  = "my_properties";
				return $this->load_view();
			}
			else
			{
				return Redirect("/");
			}
		}
		else{
			return Redirect("/");
		}
	}
	function get_my_property(Request $request)
	{
		$id     =$this->logedin->id;
		$page   = $request->input('page');
		$limit  = $request->input('limit');
		$property_for = trim($request->input('property_for'));

		$offset = ($page - 1)*$limit;

		$filters = array();
		if($property_for != "")
		{
			$filters[] = array("property_deal", $property_for);
		}

		$data['properties'] = property::get_MyProperty($id,$offset,$limit, $filters);

		foreach($data['properties'] as $key=>$value){
			$imagestore = asset('/storage/Property/'.$value['id'].'/thumbs/'.$value['filename']);
			$data['properties'][$key]['filename'] = Storage::disk('s3')->exists("Properties/".$value['id']."/".$value['filename'])?Storage::disk('s3')->url("Properties/".$value['id']."/".$value['filename']):$imagestore;
            $data['properties'][$key]['offers'] = Offer::where('asset_id', $value['id'])->count();
		}

		$response = array(
			"total" => property::my_total_properties(Auth::user()->id, $filters),
			"content" => View::make("user.my_property_page", $data)->render()
		);

		echo json_encode($response);
	}

    function property_evolution($id)
    {
	    $this->data['visits_weekly'] = Loquare::property_visited('week', $id);
	    $this->data['visits_monthly'] = Loquare::property_visited('month', $id);

	    $this->data['property'] = property::find($id);
	    $this->data['scripts'] = array(
		    'https://code.jquery.com/ui/1.12.1/jquery-ui.js'
	    );
	    $this->data['styles'] = array(
	    	'//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
		    asset("frontend/styles/my-property-evolution.css")
	    );
		$this->view = "my-property-evolution";
	    return $this->load_view();
    }

    function offer_chart_data(Request $request)
    {
    	$id = $request->input("id");
	    $month = $request->input("month");
		$year = $request->input("year");

	    $month = ($month != "")?$month:date('F');
	    $year = ($year != "")?$year:date('Y');

	    $month_start = new Carbon('first day of '.$month.' '.$year);
	    $month_last = new Carbon('last day of '.$month.' '.$year);
	    $month_range = [$month_start, $month_last];

	    $monthly = Offer::whereBetween('created_at', $month_range)
		    ->where('asset_id', $id)
		    ->selectRaw('AVG(IF (step_2_negotiate_flag = 1,customer_offer_price,owner_offer_price)) AS average_suggested_price,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
		    ->groupBy('date')
		    ->pluck('average_suggested_price', 'date');

	    $offers_in_progress_monthly = Offer::whereBetween('created_at', $month_range)
		    ->where('step_7_completed', 0)
		    ->where('asset_id', $id)
		    ->selectRaw('COUNT(id) AS total_offers,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
		    ->groupBy('date')
		    ->pluck('total_offers', 'date');

	    $offers_completed_monthly = Offer::whereBetween('created_at', $month_range)
		    ->where('step_7_completed', 1)
		    ->where('asset_id', $id)
		    ->selectRaw('COUNT(id) AS total_offers,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
		    ->groupBy('date')
		    ->pluck('total_offers', 'date');

	    $total_inprocess = Offer::whereBetween('created_at', $month_range)
		    ->where('step_7_completed', 0)
		    ->where('asset_id', $id)
		    ->selectRaw('COUNT(id) AS total_offers')->get()->toArray();


	    $total_completed = Offer::whereBetween('created_at', $month_range)
		    ->where('step_7_completed', 1)
		    ->where('asset_id', $id)
		    ->selectRaw('COUNT(id) AS total_offers')->get()->toArray();


	    $monthly = Loquare::getMonthlyData($monthly,$year,$month);
	    $offers_in_progress_monthly = Loquare::getMonthlyData($offers_in_progress_monthly,$year,$month);
	    $offers_completed_monthly = Loquare::getMonthlyData($offers_completed_monthly,$year,$month);

	    echo json_encode(array(
	    	"labels_monthly" => $monthly,
	    	"offers_inprogress_scale_monthly" => $offers_in_progress_monthly,
	    	"offers_completed_scale_monthly" => $offers_completed_monthly,
		    "total_inprocess"   => $total_inprocess[0]['total_offers'],
		    "total_completed" => $total_completed[0]['total_offers']
	    ));

		exit();
    }

	function week_offer_chart_data(Request $request){

		$date = $request->input("date");
		$id = $request->input("id");

		$week_start = Carbon::createFromDate(date('Y', strtotime($date)), date('m', strtotime($date)), date('d', strtotime($date)));
		$week_last =  Carbon::createFromDate(date('Y', strtotime($date)), date('m', strtotime($date)), date('d', strtotime($date)));

		$week_range = [$week_start->startOfWeek(), $week_last->endOfWeek()];

		$weekly = Offer::whereBetween('created_at', $week_range)
			->where('asset_id', $id)
			->selectRaw('AVG(IF (step_2_negotiate_flag = 1,customer_offer_price,owner_offer_price)) AS average_suggested_price,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
			->groupBy('date')
			->pluck('average_suggested_price', 'date');

		$offers_in_progress_weekly = Offer::whereBetween('created_at', $week_range)
			->where('step_7_completed', 0)
			->where('asset_id', $id)
			->selectRaw('COUNT(id) AS total_offers,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
			->groupBy('date')
			->pluck('total_offers', 'date');

		$offers_completed_weekly = Offer::whereBetween('created_at', $week_range)
			->where('step_7_completed', 1)
			->where('asset_id', $id)
			->selectRaw('COUNT(id) AS total_offers,DATE_FORMAT(created_at, "%Y-%m-%d") AS date')
			->groupBy('date')
			->pluck('total_offers', 'date');

		$weekly = Loquare::weekData($weekly, $date);
		$offers_in_progress_weekly = Loquare::weekData($offers_in_progress_weekly, $date);
		$offers_completed_weekly = Loquare::weekData($offers_completed_weekly, $date);

		echo json_encode(array(
			"labels_weekly" => $weekly,
			"offers_in_progress_weekly" => $offers_in_progress_weekly,
			"offers_completed_weekly" => $offers_completed_weekly
		));
		exit();
	}

	public function myOffers()
	{
		$log = array(
			"type" => "page",
			"message" => "visited my offer page"
		);
		activity::addlog($log);
		$this->data['scripts'] = array( asset("/frontend/js/my-offers.js"));
		$this->view = "my-offers";
		return $this->load_view();
	}

	public function get_my_offers(Request $request)
	{
		$page = $request->input("page");
		$limit = $request->input("limit");
		$type = $request->input("property_for");

		$offers = Offer::where("login_id", Auth::user()->id);
		if($type != "")
		{
			$offers = $offers->whereHas("property", function($query) use ($type){
				$query->where("property_deal", $type);
			});
		}

		$offers = $offers->orderBy("id","DESC")->paginate($limit);

		$data['offers'] = $offers;
		$data['total']  = Offer::where("login_id", Auth::user()->id)->count();

		$response = array(
			"total" => $data['total'],
			"content" => View::make("user.my_offers_page", $data)->render()
		);

		echo json_encode($response);
		exit();
	}

	public function cancel_offer(Request $request)
	{
		$response = array(
			"status" => 500,
			"message" => "ERROR! While propcessing!"
		);
		$offer = $request->input("id");

		$flag = Offer::where("id", $offer)->delete();

		if($flag)
		{
			$response = array(
				"status" => 200,
				"message" => "Offer Cancled successfuly!"
			);
		}

		echo json_encode($response);
		exit();
	}
}
