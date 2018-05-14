<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CI_ModelController as common;
use App\LogesModel as logs;
use Auth;
use Session;
use Mail;
use App\Propertyvisited as visited;
use App\Offer;
use App\properties\PropertyModel as property;

class Loquare
{
	protected $data;

	public function __construct()
	{

	}

	public static function collections($user = "")
	{
		$result = DB::table("collections");

		if($user != "")
		{
			$result = $result->where("user_id", $user);
		}

		$result = $result->get();

		if($result)
		{
			$result = json_decode(json_encode($result));

			return $result;
		}
		else{
			return false;
		}
	}

	public static function success(){
		return session("success");
	}

	public static function error(){
		return session("error");
	}

	public static function dateDifference($date_1 , $date_2 , $differenceFormat = '' )
	{
		$datetime1 = date_create($date_1);
		$datetime2 = date_create($date_2);

		$interval = date_diff($datetime1, $datetime2);

		$plural_duration = array();

		if(trim($differenceFormat) == "" )
		{
			if($interval->y != 0){ $plural_duration[] = "%y year"; }
			if($interval->m != 0){ $plural_duration[] = "%m Month"; }
			if($interval->d != 0){ $plural_duration[] = "%d Day"; }
			if($interval->h != 0){ $plural_duration[] = "%h Hour"; }
			if($interval->i != 0){ $plural_duration[] = "%i Minute"; }
			if($interval->s != 0){ $plural_duration[] = "%s Second"; }

			$differenceFormat = implode(" ", $plural_duration);
		}

		return $interval->format($differenceFormat);

	}

    public static function sendMail($templateViewFile, $templateViewData, $subject, $toName, $toEmail, $body, $attachment = '')
    {
        $data = [];
        $data['subject'] = $subject;
        $data['toEmail'] = $toEmail;
        $data['toName'] = $toName;
        $data['attachment'] = $attachment;


        if($templateViewFile == '') {
            $templateViewFile = 'Template';
            $templatedata['content'] = $body;
        } else {
            $templatedata = $templateViewData;
        }

        Mail::send(['html' => 'emails.'.$templateViewFile], $templatedata, function($message) use ($data) {
            $message->subject($data['subject']);
          	$message->to($data['toEmail'], $data['toName']);

            if(isset($data['attachment']) && $data['attachment'] != '') {
                $message->attach($data['attachment']);
            }
        });

    }
	
	public static function priceHistory($property = "", $limit = "")
	{
		$data = DB::table("price_history")->orderBy("created_at", "desc");
		$data = $data->where("property", $property);
		$data = ($limit != "")?$data->limit($limit):$data;
		$data = $data->get();

		if($data)
		{
			$result = json_decode(json_encode($data));

			return $result;
		}
		else{
			return false;
		}
	}

	public static function number_of_visits($period="today")
	{
		$from   = date("Y-m-d");
		$to     = date("Y-m-d", strtotime($from." +1 day"));

		if($period == "week")
		{
			if(date("l") != "monday")
			{
				$from = date("Y-m-d", strtotime("last monday"));
				$to = date("Y-m-d", strtotime("next monday"));
			}
		}
		if($period == "month")
		{
			$from   = date("Y-m-d", strtotime("first day of this month"));
			$to     = date("Y-m-d", strtotime("last day of this month"));
		}
		$from   = $from." 00:00:00";
		$to     = $to." 00:00:00";

		$data = new logs();
		$data = $data->where("arrive_time", ">=", $from)
			->where("arrive_time", "<", $to)
			->groupBy("session_id")->count('session_id');

		return $data;
	}

	public static function property_visited($period="today", $property="*")
	{
		$from   = date("Y-m-d");
		$to     = date("Y-m-d", strtotime($from." +1 day"));

		if($period == "week")
		{
			if(date("l") != "monday")
			{
				$from = date("Y-m-d", strtotime("last monday"));
				$to = date("Y-m-d", strtotime("next monday"));
			}
		}
		if($period == "month")
		{
			$from   = date("Y-m-d", strtotime("first day of this month"));
			$to     = date("Y-m-d", strtotime("last day of this month"));
		}
		$from   = $from." 00:00:00";
		$to     = $to." 00:00:00";

		$data = new visited();
		$data = $data->selectRaw("property_id, COUNT(id) as visits")
				->where("created_at",">=",$from)
				->where("created_at", "<", $to);

		if($property != "*")
		{
			$data = $data->where("property_id","=", $property);
		}

		$data = $data->groupBy("property_id");


		$data = $data->get()->toArray();

		if(count($data) > 0)
		{
			if($property != "*")
			{
				return $data[0];
			}
			else{
				return $data;
			}
		}
		else{
			return false;
		}
	}

    public static function getMonthlyData($monthly, $year="", $month="")
    {
    	$laastdateofmonth = date('t', strtotime($year."-".$month."-01"));
	    for($i = 1; $i <= $laastdateofmonth ; $i++)
	    {
		    $date = str_pad($i, 2, '0', STR_PAD_LEFT)." ".$month." ".$year;
		    $date = date("Y-m-d", strtotime($date));
		    $dates[$date] = (isset($monthly[$date]) && $monthly[$date] != '') ? round($monthly[$date]) : 0;
	    }

	    return $dates;
    }

    public static function getWeeklyData($weekly, $date)
    {
    	$startdate = $date;
    	if(date('L') != $date("L", strtotime($date)))
	    {
		    $startdate = date("Y-m-d", strtotime("last monday of ".$date));
	    }
    	$startdate = date('Y-m-d', strtotime($date));

        for($i=0; $i<6; $i++){
            $date = date('Y-m-d', strtotime('last monday + '.$i.' day'));
            $dates[$date] = (isset($weekly[$date]) && $weekly[$date] != '') ? round($weekly[$date]) : 0;
        }

        return $dates;
    }

    static function weekData($weekly, $date = '')
    {
		$date = ($date == "")?date('Y-m-d'):$date;

	    if("Monday" != date('L'))
	    {
			$today_number = date('w', strtotime($date));
		    $diff = $today_number - 1;
		    $date = date('Y-m-d', strtotime($date." -".$diff."day"));
	    }
	    $dates[$date] = (isset($weekly[$date]) && $weekly[$date] != '') ? round($weekly[$date]) : 0;
	    $date = date('Y-m-d', strtotime($date." +1day"));

	    while("Monday" != date("l", strtotime($date)))
	    {
		    $dates[$date] = (isset($weekly[$date]) && $weekly[$date] != '') ? round($weekly[$date]) : 0;
		    $date = date('Y-m-d', strtotime($date." +1day"));
	    }

	    return $dates;

    }

    static function getNewPublishedAssetsCount()
    {
        $objProperty = new property();

        $new_assets_published_sale = $objProperty->where('property_deal', 'sale')->where('status', 1)->where('admin_notified', 0)->count();
        $new_assets_published_rent = $objProperty->where('property_deal', 'rent')->where('status', 1)->where('admin_notified', 0)->count();
        $total_published = $new_assets_published_sale + $new_assets_published_rent;

        return ['sale' => $new_assets_published_sale, 'rent' => $new_assets_published_rent, 'total' => $total_published];
    }

	static function getNewUnpublishedAssetsCount()
	{
		$objProperty = new property();

		$new_assets_published_sale = $objProperty->where('property_deal', 'sale')->where('status', 0)->where('admin_notified', 0)->count();
		$new_assets_published_rent = $objProperty->where('property_deal', 'rent')->where('status', 0)->where('admin_notified', 0)->count();
		$total_published = $new_assets_published_sale + $new_assets_published_rent;

		return ['sale' => $new_assets_published_sale, 'rent' => $new_assets_published_rent, 'total' => $total_published];
	}

    static function getNewAssetsCount()
    {
        $objProperty = new property();

        $new_assets_sale = $objProperty->where('property_deal', 'sale')->where("published_by", "!=", "COMPANY")->where('admin_notified', 0)->count();
        $new_assets_rent = $objProperty->where('property_deal', 'rent')->where("published_by", "!=", "COMPANY")->where('admin_notified', 0)->count();
        $total = $new_assets_sale + $new_assets_rent;

        return ['sale' => $new_assets_sale, 'rent' => $new_assets_rent, 'total' => $total];
    }



    static function getNewMyAssetsCount()
    {
        $objProperty = new property();

        $new_my_assets_sale = $objProperty->where('agent_id', Auth::guard('admin')->user()->id)->where('property_deal', 'sale')->whereRaw("datediff('".date('Y-m-d')."', agent_assigned_date) < 5")->count();
        $new_my_assets_rent = $objProperty->where('agent_id', Auth::guard('admin')->user()->id)->where('property_deal', 'rent')->whereRaw("datediff('".date('Y-m-d')."', agent_assigned_date) < 5")->count();
        $total = $new_my_assets_sale + $new_my_assets_rent;

        return ['sale' => $new_my_assets_sale, 'rent' => $new_my_assets_rent, 'total' => $total];
    }

    public static function getNewOffers() {
        $objOffer = new Offer();
        if(Auth::guard('admin')->user()->role->type == 'agent'){
            $agentAssets = property::where('agent_id', Auth::guard('admin')->user()->id)->pluck('id');
            $filters = [];
            if(count($agentAssets) > 0) {
                $filters['asset_id'] = $agentAssets->toArray();
            } else {
                $filters['asset_id'] = [0];
            }
        }

        $filters['new'] = 1;
        $filters['accept_status'] = 0;
        $offers = $objOffer->getAll($filters);
        $data['offers_inreview'] = $offers->count();

        unset($filters['new']);
        $filters['new_completed'] = 1;
        $filters['step_7_completed'] = 1;
        $filters['accept_status'] = 0;
        $filters['property_deal'] = 'sale';
        $offers = $objOffer->getAll($filters);
        $data['offers_completed_sale'] = $offers->count();

        $filters['property_deal'] = 'rent';
        $offers = $objOffer->getAll($filters);
        $data['offers_completed_rent'] = $offers->count();

        $data['offers_completed'] = $data['offers_completed_sale'] + $data['offers_completed_rent'];
        return $data;
    }

}
?>