<?php

namespace App\Http\Controllers;

use Bjrnblm\Messagebird\Messagebird;
use Illuminate\Http\Request;
use MessageBird\Client;
use MessageBird\Common\Authentication;
use MessageBird\Common\HttpClient;
use MessageBird\Resources\Chat\Message;
use Session;
use App\Http\Controllers\CI_ModelController as common;
use App\population\PopulationModel as population;
use App\properties\RetailerModel as retailers;
use App\properties\PropertyModel as property;
use App\DistrictModel as district;
use App\DistrictGalleryModel as district_gallery;
use App\Visitors_feedback_Model as visitors_feedback;
use App\Property_hoods as property_hoods;
use App\HoodsModel as Hoods;
use Redirect;
use Auth;
use Excel;
use DB;
use Mail;
use App\Offer;
use App\Helpers\Loquare;
use App\Http\Controllers\LogsController as activity;
use App\properties\ProperttypesModel as property_type;
use App\Propertyvisited;
use App\NeighbourModel;
use App\properties\PropertyModel;
use App\HoodsModel as hood;

class HomeController extends Controller
{
	protected $data, $view, $searchdata;

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

	    $this->searchdata['search']     = "";
	    $this->searchdata['zipcode']    = "";
	    $this->searchdata['provincia']  = "";
	    $this->searchdata['type']       = 1;
	    $this->searchdata['min_price']  = "";
	    $this->searchdata['max_price']  = "";
	    $this->searchdata['rooms']      = "";
	    $this->searchdata['bathrooms']  = array();
	    $this->searchdata['searchin']   = "";

	    $this->data['scripts'] = false;
	    $this->data['styles'] = false;

	    if(Session::has('searchdata'))
	    {
		    $searchdata = session('searchdata');
		    $this->searchdata = $searchdata;

	    }
	    $this->data['filters'] = $this->searchdata;
	    $this->data['logedin']	= Auth::user();

	    $data = Loquare::property_visited("week");

    }

	public function index()
	{
		$this->data['scripts'] = array(
			asset('frontend/js/bootstrap.js'),
			asset('frontend/js/jquery.easing.1.3.js')
		);

		$this->data['styles'] = array(
			asset('frontend/styles/bootstrap.css'),
			asset('frontend/styles/main-style.css'),
			asset('/frontend/styles/frontend.css'),
			asset('frontend/styles/index.css')
		);

		$this->data['scripts'] = array(
			asset('frontend/js/index.js')
		);

		$this->data['areas'] = common::get_all(new district());

		$this->data['body_class_name']='home-page';
		$this->view = 'index';


		$log = array(
			"type"      => "page",
			"message"   => "Visited Home page",
		);

		activity::addlog($log);

		return $this->load_view();
	}


	function load_view()
	{
		return view($this->view, $this->data);
	}


	public function search(Request $request)
	{

		$this->searchdata['search']     = "";
		$this->searchdata['zipcode']    = "";
		$this->searchdata['provincia']  = "";
		$this->searchdata['type']       = "RENT";
		$this->searchdata['min_price']  = "";
		$this->searchdata['max_price']  = "";
		$this->searchdata['rooms']      = "";
		$this->searchdata['bathrooms']  = array();
		$this->searchdata['searchin']   = "";


		$search = array(
			"search" => $request->input('search'),
			"zipcode"   => $request->input('zipcode'),
			"provincia" => $request->input('provincia'),
			"type"  => $request->input('type'),
			"min_price"  => $request->input('min_price'),
			"max_price"  => $request->input('max_price'),
			"rooms"  => $request->input('rooms'),
			"bathrooms"  => $request->input('bathrooms'),
			"searchin"  => $request->input('searchin'),
		);

		Session::put("searchdata", $search);

		return true;
	}

    public function listing($type = "", $hood_id="")
    {
	    $this->data['success'] = session('success');
	    $this->data['error'] = session('error');

	    $this->data['searchdata'] = (Session::has('searchdata'))?session('searchdata'):false;

		Session::forget("searchdata");

	    $this->data['styles'] = array(
		    asset('/frontend/styles/listing.css'),
	    );
    	$this->data['scripts'] = array(
			asset('/frontend/js/mapbox-functions.js'),
			asset('/frontend/js/retailer-map.js'),
			asset('frontend/scripts/slick.js'),
	    );

	    $filter = array(
		    array("parent", "=", 0),
		    array("status", "=", 1)
	    );
	    $property_types = common::get_by_condition( new property_type(),$filter);
	    $this->data["property_types"] = $property_types;

		$this->data['type'] = $type;

	    if($hood_id != "")
	    {
		    $table  = new Hoods();
		    $filter = array("id" => $hood_id);
		    $hoods_data   = common::get_single($table, $filter);

		    if($hoods_data != false)
		    {

			    $this->data['searchdata'] = array(
				    "search"    => $hoods_data['hood'],
				    "zipcode"   => $this->searchdata['zipcode'],
				    "provincia" => $this->searchdata['provincia'],
				    "type"      => $type,
				    "min_price"  => $this->searchdata['min_price'],
				    "max_price"  => $this->searchdata['max_price'],
				    "rooms"      => $this->searchdata['rooms'],
				    "bathrooms"  => $this->searchdata['bathrooms'],
				    "searchin"  => 'hood_table',
			    );
		    }


	    }
        if($this->data['searchdata'] != false)
        {
	        $log = array(
		        "type"      => "property-search",
		        "message"   => "Search for Property :- ".$this->data['searchdata']['search']
	        );

	        activity::addlog($log);
        }
        else
	    {
	        $log = array(
		        "type"      => "page",
		        "message"   => "Visited Property listing page"
	        );

	        activity::addlog($log);
        }

	    $this->view = 'listing';
	    return $this->load_view();
    }

    public function property($id = "")
	{
		if($id != "")
		{

			$filter = array("id" => $id, 'deleted' => 0);
			$table  = new property();
			$data   = common::get_single($table, $filter);
			$user = Auth::user();

			if($data == false) { return Redirect::route('properties'); }

			/* LOG START */

			$log = array(
				"type"    => "property-visit",
				"message" => "visited property - ".$data['direccion'].",".$data['cops']." ".$data['provincia']
			);
			activity::addlog($log);

			/* LOG END */

			$population = new population();

			$population = $population->select(DB::raw("AVG(pedad10) as below16,
				AVG(pedad40) as between16_64,
				AVG(pedad75) as older75,
				AVG(psexof) as woman,
				AVG(pnacional) as spanish
			"))->where("cops",$data['cops'])->get()->first()->toArray();

			$spanish = round($population['spanish']*100);
			$woman   = round($population['woman']*100);

			$qry = 'SELECT GF.F_FactorInmig, ((GF.factor*100)/FT.total_f) as per_factor FROM
					(
						SELECT F_FactorInmig, COUNT(F_FactorInmig) AS factor FROM population WHERE cops = '.$data['cops'].' GROUP BY F_FactorInmig
			) GF,
			(
			SELECT COUNT(F_FactorInmig) AS total_f FROM population WHERE cops = '.$data['cops'].'
			) FT';

			$factors = DB::select($qry);
			$factors = array_reverse($factors);
			$factor = array();

			foreach($factors as $fact)
			{
				for($i=0;$i<5;$i++)
				{
						if($i == $fact->F_FactorInmig)
						{
							$factor["index_".($i+1)] = array("title" => "'index ".($i+1)."'", "value" => round($fact->per_factor));
						}
				}
			}
			for($i=0;$i<5;$i++)
			{
				if(!array_key_exists("index_".($i+1),$factor))
				{
					$factor["index_".($i+1)] = array("title" => "'index ".($i+1)."'", "value" => 10);
				}
			}


			$this->data['indexes'] = $factor;
			$this->data['age'] = array(
				"below16" => array("title" => "'Below 16 y.o'", "value" => round($population['below16']*100)),
				"between16_64" => array("title" => "'16 - 64 y.o'", "value" => round($population['between16_64']*100)),
				"older75" => array("title" => "'75+ y.o'", "value" => round($population['older75']*100))
			);
			$this->data['national'] = array(
				"spanish" =>array("title" => "'Spanish'", "value" =>  $spanish),
				"other" => array("title" => "'Other'", "value" => (100 - $spanish))
			);

			$this->data['sex'] =  array(
				"female" => array("title" => "'Female'", "value" =>  $woman),
				"male" => array("title" => "'Male'", "value" =>  (100 - $woman))
			);

			$images = DB::table('loquare_uploads')->select('filename')->where("post_id", "=", $id);
			$images = $images->where("post_type", "=", "property-image")->get();

			$images = json_decode(json_encode($images), true);

			$data['images'] = $images;

			$this->data['historical_prices']       = Loquare::priceHistory($id,3);
			$this->data['prices']       = Loquare::priceHistory($id,-1);

			$this->data['nearby_flats'] = PropertyModel::select("*")->addSelect(DB::raw('
				(
				6371 * acos (
			      cos ( radians(' . $data['longitude']. ') )
			      * cos( radians( longitude ) )
			      * cos( radians( latitude ) - radians(' . $data['latitude']. ') )
			      + sin ( radians(' . $data['longitude']. ') )
			      * sin( radians( longitude ) )
			    )
			  ) AS distance'))
				->where("property_deal", $data['property_deal'])
				->havingRaw("distance <= 50")
				->orderByRaw("distance ASC")
				->where("id", "!=", $id)
				->get();



			$data['property_type']      = PropertyModel::property_type($id);

			$filter = array("id" => $data['hoods']);
			$table  = new Hoods();
			$hoods_data   = common::get_single($table, $filter);
			$this->data['hoods_data']=$hoods_data;

			$table = new district();
			$filter = array("id" => $hoods_data['dist_id']);
			$district  = common::get_single($table,$filter);

			$this->data['district']=$district;
			$this->data['sold'] = Offer::where("asset_id",$data['id'])
				->where("sold_status", 1)
				->get();

			if($data != false)
			{
				$this->data['property'] = $data;
				$this->data['scripts'] = array(
					asset('/frontend/js/retailer-single.js'),
					asset('/frontend/js/analys.js'),
					asset('/frontend/js/analysSchools.js'),
					'https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v1.3.0/turf.min.js',
					'https://api.tiles.mapbox.com/mapbox.js/v2.0.1/mapbox.js',
					asset('/frontend/js/mapbox-functions.js'),
					asset('/frontend/scripts/mapbox-gl-traffic.js'),
					'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'
				);

				$this->data['styles'] = array(
					"https://js.api.here.com/v3/3.0/mapsjs-ui.css",
					asset('/frontend/styles/mapbox-gl-traffic.css'),
				);

                $offers = Offer::where('step_7_completed', 1)->where('accept_status', 0)->where('asset_id', $id)->get();
                $offersPrice = [];
                if(!empty($offers)) {
                    foreach($offers as $offer) {
                       if ($offer->step_2_negotiate_flag == 0) {
                               $offersPrice[] = $offer->owner_offer_price;
                       } else {
                               $offersPrice[] = $offer->customer_offer_price;
                       }
                    }
                }

                if(!empty($offersPrice)) {
                    //$this->data['offer'] = max($offersPrice);
                    $this->data['offer'] = round(array_sum($offersPrice)/count($offersPrice));
                }

                $this->data['schedules'] = property::find($data['id']);

				$this->data['success'] = session('success');
				$this->data['error'] = session('error');

				$this->data['og'] = array(
					"title" => $data['direccion'],
					"url" => url('/')."property/detail/".$data['id'],
					"image" => url('/storage/Property/'.$data['id'].'/thumbs/'.$images[0]['filename']),
					"discription" => $data['discription']
				);

				$visite = new Propertyvisited();
				$visite->property_id = $data['id'];
				$visite->user_id = $user->id;
				$visite->save();

				$this->view = 'single';
				return $this->load_view();
			}
			else{
				return Redirect::route('home');
			}
		}
		else{
			return Redirect::route('home');
		}
	}

	public function visitor_feedback(Request $request)
	{
		$name = $request->input("name");
		$phone_no = $request->input("phoneno");
		$email = $request->input("email");
		$get_message = $request->input("get_message");

		$property_id = $request->input("visitor_property_id");
		$direccion = $request->input("visitor_direccion");
		$price = $request->input("visitor_price_with_unit");
		$schedule_date = $request->input("schedule_date");
		$schedule_time = $request->input("datetime");


        $sdate =$schedule_date.' '.$schedule_time; ;
        $sdate=date('Y-m-d h:s:i', strtotime($sdate));
		$created = date("Y-m-d h:i:s");
		$data=array(
			'name'=>$name,
			'email'=>$email,
			'phone_no'=>$phone_no,
			'schedule_date'=>$sdate,
			'message'=>$get_message,
			'property_id'=>$property_id,
			'direccion' => $direccion,
			'price' => $price,
			'created_at'=>$created,

		);

		$table = new visitors_feedback();
		$feedback_data = common::insert_data($table, $data);

		$recipients = [ ["name" => "Loquare", "email" => "tester@loquare.com"], ["name" => $name, "email" => $email] ];

		if($feedback_data != false)
		{
			foreach($recipients as $recipient) {

				Mail::send('email',
							   array(
									'name'=>$name,
									'email'=>$email,
									'phone_no'=>$phone_no,
									'schedule_date'=>$sdate,
									'get_message'=>$get_message,
									'property_id'=>$property_id,
									'direccion' => $direccion,
									'price' => $price
							   ), function($message) use ($recipient)
							   {
									$message->from('tester@loquare.com');
									$message->to($recipient['email'], $recipient['name'])->subject('Loquare: Get more info');
							   });

			}

			echo json_encode(
				array(
					"status" => 200,
					"message" => "Congratulation! <br/> Your Feedback is Sent Successfully...!"
				)
			);
		}else{

			echo json_encode(
					array(
						"status" => 500,
						"message" => "Sorry! Something went wrong<br/>Please try again.......!"
					)
				);
			}
	}

	public function page($page = "")
	{
		if($page != "")
		{
			$page = explode(".", $page);

			if(view()->exists("design.".$page[0])){
				return view("design.".$page[0], $this->data);
			}
			else{
				return Redirect::route('home');
			}
		}
		else{
			return Redirect::route('home');
		}
	}


	function properties(Request $request)
	{
		$zipcode = $city = "";
		$search = $request->input('search');
		$page = $request->input('page');
		$min = (int)trim($request->input('min_price'));
		$max = (int)trim($request->input('max_price'));
		$type = $request->input('type');
		$property_type = $request->input('property_type');
		$room = $request->input('rooms');
		$baths = $request->input('bathrooms');
		$features = $request->input('features');
		$sort_by = $request->input('sort_by');
		$searchin = $request->input("searchin");

		$this->data['baths'] = $baths;
		$this->data['features'] = $features;
		$this->data['rooms'] = $room;
		if ($baths != "" && $baths != NULL) {
			$baths = implode(" - ", $this->data['baths']);
		}

		$minsize = $request->input('min_size');
		$maxsize = $request->input('max_size');

		$typeof = $request->input('typeof');

		if($page == "" || $page == 0)
		{
			$page = 1;
		}

		$limit = $request->input("limit");

		$property = new property();
		$property = $property->where(["status" => 1, "deleted" => 0]);

		$property = $property->with(["images" => function($query){
			$query->where("filename", "!=", "");
		}]);

		if ($searchin == "listing") {
			$this->provincia = "";

			$this->check = explode(";", $search);

			if(count($this->check) > 0)
			{
				for ($i = 0; $i < count($this->check); $i++) {
					$this->creck1 = explode(" ", $this->check[$i]);
					for ($j = 0; $j < count($this->creck1); $j++) {
						if (is_numeric(trim($this->creck1[$j])) && strlen($this->creck1[$j]) >= 4) {
							$this->zipcode = $this->creck1[$j];
							$this->provincia = $this->creck1[$j + 1];
						}
					}
				}
			}

			$property = $property->where(function($query){
				$query->where("direccion", "like", '%' .addslashes($this->check[0]). '%');
				if($this->provincia != ""){ $query->where("provincia", $this->provincia); }
			});

			if($zipcode != ""){ $property = $property->where("cops", $zipcode); }

		}
		elseif($searchin == "district")
		{
			$property = $property->whereHas("district", function($query) use ($search){
				$query->where("dist_name", "like", "%".$search."%");
			});
		}
		elseif($searchin == "cops")
		{
			if($search[0] == 0){ $search = substr($search, 1);}
			if($property_type != ""){$property = $property->where("cops", "like", "%".trim($search)."%"); }
		}
		elseif($searchin == "hood_table"){
			$property = $property->whereHas("hood", function($query) use ($search){
				$query->where("hood", "like", "%".$search."%");
			});
		}
		else{
			$searches = explode(",", $search);

			$property->where(function($query) use ($searches){
				foreach($searches as $search)
				{
					$query->orWhere("provincia", "".trim($search)."");
					$query->orWhere("direccion", "".trim($search)."");
				}
			});
		}

		if($min != ""){$property = $property->where("price", ">=", $min); }
		if($max != ""){$property = $property->where("price", "<=", $max); }
		if(trim($minsize) != ""){$property = $property->where("sizem2", ">=", $minsize); }
		if(trim($maxsize) != ""){$property = $property->where("sizem2", "<=", $maxsize); }
		if($type != ""){$property = $property->where("property_deal", $type); }
		if($property_type != ""){$property = $property->whereIn("property_type", $property_type); }


		if (count($this->data['rooms']) != 0) {
			if(in_array("5+", $this->data['rooms']))
			{
				$this->data['rooms'][] = 5;
			}
			$property = $property->whereIn("rooms", $this->data['rooms']);
		}
		if (count($this->data['baths']) != 0) {
			if(in_array("5+", $this->data['baths']))
			{
				$this->data['baths'][] = 5;
			}
			$property = $property->whereIn("bathrooms", $this->data['baths']);
		}


		if($features != "" && $features != NULL)
		{
			$property = $property->where(function($query) use ($features){
				foreach($features as $feature)
				{
					$query->orWhere($feature, 1);
				}
			});
		}

		if($sort_by!=''){
			$sort_array=explode('-',$sort_by);
			$property = $property->orderBy($sort_array[0], $sort_array[1]);
		}
		else
		{
			$property = $property->orderBy("construction", "desc");
		}

		$total = $property->count();
		$cluster = $property->get();
		$data = $property->paginate($limit);

		if ($total > 0) {
			$results['total'] = $total;
			$results['cluster'] = $cluster;
			$results['details'] = $data;
			echo json_encode($results);
		} else {
			echo json_encode(FALSE);
		}

		exit();

	}

	function searchin(Request $request)
	{
		$search = $request->input('search');
		$searchin = false;
		$response = array(
			"status" => 200,
			"searchin" => "listing"
		);

		$data = DB::table("property")->where("provincia", $search)->count();

		if($data > 0)
		{
			$searchin = true;
			$response['searchin'] = "areas";
		}

		if(!$searchin)
		{
			$data = DB::table("property")->where("hoods", $search)->count();
			if($data > 0)
			{
				$searchin = true;
				$response['searchin'] = "hoods";
			}
		}

		if(!$searchin)
		{
			$data = DB::table("district_zip")->where("district",$search)->count();
			if($data > 0)
			{
				$searchin = true;
				$response['searchin'] = "district";
			}
		}

		if(!$searchin)
		{
			$data = DB::table("district_zip")->where("zipcode",$search)->count();
			if($data > 0)
			{
				$searchin = true;
				$response['searchin'] = "cops";
			}
		}
		if(!$searchin)
		{
			$data = DB::table("hoods")->where("hood",$search)->count();
			if($data > 0)
			{
				$searchin = true;
				$response['searchin'] = "hood_table";
			}
		}
		

		echo  json_encode($response);
		exit();
	}

	function searchresult(Request $request)
	{
		$query = $request->input('query');

		$queryparts = explode(",", $query);

		$state = $request->input("states");

		if($state == "" || $state == NULL)
		{
			$state = $this->searchdata['provincia'];
		}

		$result['cops']         = $this->zipcodes($queryparts);
		$result['district']     = $this->searchDistrict($state, $queryparts);
		$result['hoods']        = $this->hoods($queryparts);
		$result['areas']        = $this->searchfor($state, $queryparts, array("provincia"));
		$result['listing']      = $this->searchfor($state, $queryparts, array('direccion', 'rooms', 'bathrooms', 'price', 'provincia', 'cops'));

		echo json_encode($result);
	}

	function hoods($queryparts)
	{
		$hoods = "SELECT DISTINCT(`hood`) from `hoods`";
		if(sizeof($queryparts) > 0)
		{
			$count  = 0;
			$hoods.="WHERE";
			 foreach($queryparts as $hood)
			{
				if($count == 0)
				{
					$hoods .= "`hood` LIKE '%".$hood."%'";
				}
				else{
					$hoods .= "`hood` LIKE '%".$hood."%'";
				}
				$count++;
			}
		 }
		$hoods .= ' ORDER BY `hood` ASC';
		$data = DB::select($hoods);

		return $data;
	}

	function zipcodes($queryparts)
	{
		$hoods = "SELECT DISTINCT(`cops`) from `property`";
		if(sizeof($queryparts) > 0)
		{
			$count  = 0;
			$hoods .= " WHERE status = 1 AND (";
			foreach($queryparts as $hood)
			{
				if($count == 0)
				{
					$hoods .= "(`cops` LIKE '%".$hood."%')";
				}
				else{
					$hoods .= " OR (`cops` LIKE '%".$hood."%')";
				}
				$count++;
			}
			$hoods .= ")";
		}
		$hoods .= ' ORDER BY `cops` ASC';
		$data = DB::select($hoods);

		return $data;
	}

	function searchDistrict($state="", $queryparts)
	{
		$queryparts = explode(" ", implode(" ", $queryparts));

		$areaquery = "SELECT DISTINCT `district` FROM `district_zip`";
		$count = 0;

		$areaquery .= " WHERE `state` = '".$state."'";

		if(count($queryparts) > 0)
		{
			$areaquery .= " AND (";
			foreach($queryparts as $area)
			{
				if($count == 0)
				{
					$areaquery .= "(`zipcode` = '".$area."' OR district LIKE '%".trim($area)."%')";
				}
				else{
					$areaquery .= " OR (`zipcode` = '".$area."' OR district LIKE '%".trim($area)."%')";
				}
				$count++;
			}
			$areaquery .= ")";

		}

		$areaquery .= ' ORDER BY `district` ASC';
		$data = DB::select($areaquery);

		return $data;
	}

	function searchfor($state="", $queryparts, $select)
	{

		$slct = "";
		foreach($select as $field)
		{
			$slct .= "`".$field."`,";
		}

		if(count($select) == 0)
		{
			$slct = "*";
		}

		$slct = trim($slct, ",");

		$areaquery = "SELECT DISTINCT ".$slct." from `property`";

		$areaquery .= " WHERE `status` = 1 AND `provincia` = '".$state."'";
		if(count($queryparts) > 0) {
			$areaquery .= " AND ( ";
		}

		for($i = 0;$i < count($queryparts);$i++)
		{
			$place = trim($queryparts[$i]);
			if($i == 0)
			{
				$areaquery .= "(( `comunidad_autonoma` LIKE '%".$place."%' OR `direccion` LIKE '%".$place."%' OR `provincia` LIKE '%".$place."%' OR `cops` LIKE '%".$place."%' OR `hoods` LIKE '%".$place."%')";
			}
			else{
				$areaquery .= " OR ( `comunidad_autonoma` LIKE '%".$place."%' OR `direccion` LIKE '%".$place."%' OR `provincia` LIKE '%".$place."%' OR `cops` LIKE '%".$place."%' OR `hoods` LIKE '%".$place."%')";
			}
		}
		if(count($queryparts) > 0) {
			$areaquery .= "))";
		}

		for($i = 0;$i < count($queryparts); $i++)
		{
			if(trim($queryparts[$i]) == "SALE" || trim($queryparts[$i]) == "RENT")
			{
				$areaquery .= " AND `property_deal` = '".trim($queryparts[$i])."'";
				break;
			}
		}

		$data = DB::select($areaquery);

		return $data;
	}

	public function area()
	{
		$table = new district();
		$filter = array(
			array("state", "=",2)
		);
		$data  = common::get_by_condition($table,$filter);

		$this->data['styles'] = array(
			asset('frontend/styles/bootstrap.css'),
			asset('/frontend/styles/frontend.css'),
		    asset('frontend/styles/main-style.css')
		);
		if($data != false)
		{
			$this->data['districts'] = $data;
		}

		$log = array(
			"type" => "page",
			"message" => "Visited Area listing page"
		);
		activity::addlog($log);

		$this->view = 'area';
		return $this->load_view();
	}

	public function state($id='')
	{
		$table = new district();
		$filter = array(
			array("id", "=",$id)
		);

		$this->data['curr_district']  = common::get_single($table,$filter);

		$table = new district_gallery();
		$filter = array(
			array("dist_id", "=",$id)
		);

		$this->data['galleries']  = common::get_by_condition($table,$filter);


		$this->data['hoods']     = hoods::get_hoods($id);

		$this->data['styles'] = array(
			asset('/frontend/styles/slick.css'),
			asset('frontend/styles/bootstrap.css'),
			asset('/frontend/styles/frontend.css'),
			asset('frontend/styles/main-style.css')
		);

		$this->data['scripts'] = array(
			asset('frontend/scripts/slick.js'),
			asset('frontend/js/states-functions.js')
		);

		$log = array(
			"type" => "page",
			"message" => "Visited District page - ".$this->data['curr_district']['dist_name']
		);
		activity::addlog($log);

		$this->view = 'state';
		return $this->load_view();
	}

	public function neighbour($id='')
	{
		$table = new hoods();
		$filter = array(
			array("id", "=",$id)
		);
		$this->data['hood'] = hood::find($id);
		$this->data['curr_neighbour']  = common::get_single($table,$filter); //current neightbour

		$filter = array(
			array("dist_id", "=", $this->data['curr_neighbour']['dist_id']),
			array("id", "!=", $this->data['curr_neighbour']['id']),
		);
		$this->data['nearby_hoods']  = common::get_by_condition($table,$filter); //near by neighbour_hoods list

		$property_hoods = new property_hoods();
		$this->data['hood_sale_price'] = $property_hoods->select(DB::raw("AVG(price) as avg_price_sale"))->where("property_deal",1)->where("hood",$id)->get()->first()->toArray();

		$this->data['hood_rent_price'] = $property_hoods->select(DB::raw("AVG(price) as avg_price_rent"))->where("property_deal",2)->where("hood",$id)->get()->first()->toArray();

		$table = new property_hoods();
		$filter = array(
			array("hood", "=",$this->data['curr_neighbour']['id']),
			array("property_deal", "=",1)
		);
		$this->data['hood_lists']  = property_hoods::get_by_condition($table,$filter);//property_hoods for pricing_graph
	
		//get demographic details

		$population = new population();

		$population = $population->select(DB::raw("AVG(pedad10) as below16,
				AVG(pedad40) as between16_64,
				AVG(pedad75) as older75,
				AVG(psexof) as woman,
				AVG(pnacional) as spanish,
				AVG(pvivacias) as empty_house
			"))->where("cops",$this->data['curr_neighbour']['zip_code'])->get()->first()->toArray();

		$spanish = round($population['spanish']*100);
		$woman   = round($population['woman']*100);

		$qry = 'SELECT GF.F_FactorInmig, ((GF.factor*100)/FT.total_f) as per_factor FROM
					(
						SELECT F_FactorInmig, COUNT(F_FactorInmig) AS factor FROM population WHERE cops IN ('.$this->data['curr_neighbour']['zip_code'].') GROUP BY F_FactorInmig
			) GF,
			(
			SELECT COUNT(F_FactorInmig) AS total_f FROM population WHERE cops IN ('.$this->data['curr_neighbour']['zip_code'].')
			) FT';

		$factors = DB::select($qry);
		$factors = array_reverse($factors);
		$factor = array();

		foreach($factors as $fact)
		{
			for($i=0;$i<5;$i++)
			{
				if($i == $fact->F_FactorInmig)
				{
					$factor["index_".($i+1)] = array("title" => "'index ".($i+1)."'", "value" => round($fact->F_FactorInmig));
				}
			}
		}
		for($i=0;$i<5;$i++)
		{
			if(!array_key_exists("index_".($i+1),$factor))
			{
				$factor["index_".($i+1)] = array("title" => "'index ".($i+1)."'", "value" => 0);
			}
		}


		$this->data['indexes'] = $factor;
		$this->data['age'] = array(
			"below16" => array("title" => "'Below 16 y.o'", "value" => round($population['below16']*100)),
			"between16_64" => array("title" => "'16 - 64 y.o'", "value" => round($population['between16_64']*100)),
			"older75" => array("title" => "'75+ y.o'", "value" => round($population['older75']*100))
		);
		$this->data['nationality'] = array(
			"spanish" =>array("title" => "'Spanish'", "value" =>  $spanish),
			"other" => array("title" => "'Other'", "value" => (100 - $spanish))
		);
 
		$this->data['sex'] =  array(
			"female" => array("title" => "'Female'", "value" =>  $woman),
			"male" => array("title" => "'Male'", "value" =>  (100-$woman))
		);
		$this->data['empty_house'] =  array(
			"empty_house" => array("title" => "'Empty_house'", "value" =>   round($population['empty_house']*100))
		);
		$this->data['styles'] = array(
			asset('/frontend/styles/slick.css'),
			asset('/frontend/styles/frontend.css'),
			asset('/frontend/styles/bootstrap.css'),
			asset('frontend/styles/main-style.css')
		);

		$this->data['scripts'] = array(
			asset('/frontend/js/jquery.easing.1.3.js'),
			asset('/frontend/js/neighbour.js'),
			'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.min.js'
		);

		$log = array(
			"type" => "page",
			"message" => "Visited Neighbourhood page - ".$this->data['curr_neighbour']['hood']
		);
		activity::addlog($log);


		$this->view = 'neighbour';
		return $this->load_view();
	}
	
	public function get_sell_rent_price(Request $request)
	{
		 
		$curr_neighbour_id= $request->input("curr_neighbour_id");
		$property_deal = $request->input("property_deal");
		$table = new property_hoods();
		$filter = array(
			array("hood", "=",$curr_neighbour_id),
			array("property_deal", "=",$property_deal)
		);
		$data = property_hoods::get_by_condition($table,$filter);//property_hoods for pricing_graph
		
		if($data != false)
		{
			$historical_cost_label ="[";
		 	foreach($data as $hood)
			{ 
				
				$historical_cost_label.= $hood['year'].',';
			}
			$historical_cost_label.="]";
			
			$historical_cost_sale ="[";
		 	foreach($data as $hood)
			{ 
				$historical_cost_sale.= $hood['price'].',';
			}
			$historical_cost_sale.="]";
			
			$historical_price['historical_cost_label']=$historical_cost_label;
			$historical_price['historical_cost_price']=$historical_cost_sale;
			 echo json_encode($historical_price);
		}
 		exit(); 
	}

	public function request_contact(Request $request)
	{
		$contact_name = $request->input("contact_name");
		$contact_email = $request->input("contact_email");
		$contact_phone = $request->input("contact_phone");
		$contact_message = $request->input("contact_message");

		$MailContent = array(
			"contact_name"     => $contact_name,
			"contact_email"    => $contact_email,
			"contact_phone"    => $contact_phone,
			"contact_message"  => $contact_message
		);

		Loquare::sendMail('ContactRequest', $MailContent, 'Loquare - Contact Request', 'Loquare Admin', 'tester@loquare.com', '');

		echo json_encode(TRUE);
		ext();
	}

}