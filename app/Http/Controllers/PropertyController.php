<?php

namespace App\Http\Controllers;

use App\properties\PriceHistoryModel;
use App\properties\PropertyModel;
use App\UploadModel;
use Illuminate\Http\Request;
use Session;
use App\Http\Controllers\CI_ModelController as common;
use App\DistrictzipModel as distzip;
use App\DistrictModel as District;
use App\HoodsModel as Hoods;
use App\properties\ProperttypesModel as property_type;
use App\properties\PropertyModel as property;
use App\properties\ContactModel as contact;
use App\UploadModel as uploadfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LogsController as activity;
use Redirect;
use Auth;
use Excel;
use Illuminate\Support\Facades\Input;
use DB;
use App\AddpropertyHelp as addHelp;
use Mail;
use Illuminate\Support\Facades\Response;
use App\CompanyModel as company;
use Loquare;
use Illuminate\Support\Facades\View;

class PropertyController extends Controller
{
	protected $data, $view, $searchdata;

	function __construct()
	{
		session::regenerate();
		$this->middleware('auth');
		$this->data['title'] = "Loquare";
		$this->data['layout'] = "template";
		$this->data['page'] = "Loquare";

		$this->data['success'] = session('success');
		$this->data['error'] = session('error');

		$this->data['scripts'] = false;
		$this->data['styles'] = false;

		$this->data['logedin']	= Auth::user();
	}

	public function index()
	{
		$this->view = 'home';
		return $this->load_view();
	}

	function load_view()
	{
		return view("property.".$this->view, $this->data);
	}

	function add()
	{
		$table = new property_type();
		$this->data['scripts'] = array(
			'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js',
			asset('/frontend/js/add_property.js')
		);
		$this->data['styles'] = array(
			asset("/frontend/styles/add_property.css")
		);


        $filter = array(
    				array("parent", "=", 0),
	                array("status", "=", 1)
    			);

		$this->data['property_types'] = common::get_by_condition($table,$filter);

		$this->data['districts'] = common::get_all(new District(), -1, array("dist_name" => "asc"));

		$log = array(
			'type' => "page",
			'message' => "Open page for add property"
		);

		activity::addlog($log);

		$this->view = "add_property";
		return $this->load_view();
	}



	function submit(Request $request)
	{
		$data = $this->property_post($request);

		$table = new property();
        $propertyid = common::insert_data($table, $data);

		if($propertyid != false)
		{

			$log = array(
				'type' => "property-add",
				'message' => "Added a new property '".$data['direccion'].", ".$data['cops']." ".$data['provincia']
			);

			activity::addlog($log);

			$price = array(
				"price" => $data['price'],
				"property" => $propertyid,
				"loquare_commission" => $request->input('loquare_commission'),
				"realestate_commission" => $request->input('realestate_commission'),
				"created_at" => date('Y-m-d H:i:s'),
				"updated_at" => date('Y-m-d H:i:s')
			);
			$flag = DB::table("price_history")->insert($price);

 			$table = new uploadfile();

			$uploadfiles = $request->file('property_images');

			if($uploadfiles != "")
			{
				$table = new uploadfile();
				foreach($uploadfiles as $file)
				{
					$files = common::s3Fileupload($file, "Properties/".$propertyid, array("width" => 355));
					$data = array(
						"filename"   => $files['name'],
						"filetype"   => $files['type'],
						"post_id"    =>  $propertyid,
						"post_type"  => "property-image",
						"created_at" => date('Y-m-d H:i:s')
					);

					common::insert_data($table, $data);
				}
			}


			$uploadfiles = $request->file('energy_certificate');

			if($uploadfiles != "") {

				$files = common::s3Fileupload($uploadfiles, "Energycertificates/".$propertyid, array("width" => 355));
				$data = array(
					"filename"   => $files['name'],
					"filetype"   => $files['type'],
					"post_id"    =>  $propertyid,
					"post_type"  => "energy-certificate",
					"created_at" => date('Y-m-d H:i:s')
				);

				common::insert_data($table, $data);
			}

			$uploadfiles = $request->file('owner_certificate');


			if($uploadfiles != "") {

				$files = common::s3Fileupload($uploadfiles, "Ownercertificates/".$propertyid, array("width" => 355));
				$data = array(
					"filename"   => $files['name'],
					"filetype"   => $files['type'],
					"post_id"    =>  $propertyid,
					"post_type"  => "owner-certificate",
					"created_at" => date('Y-m-d H:i:s')
				);

				common::insert_data($table, $data);
			}

			$data = array(
				"property" => $propertyid,
				"contact_name" => $request->input('contact_name'),
				"contact_phone" => $request->input('contact_phone'),
				"contact_email" => $request->input('contact_email'),
				"duration" => $request->input('duration'),
				"created_at" => date('Y-m-d H:i:s'),
			);

			common::insert_data(new contact(), $data);


			$MailContent['name'] = 'Admin';
			$MailContent['url'] = url('admin/assets');
			Loquare::sendMail('AdminNewAssetNotification', $MailContent, 'New Asset Assigned', 'Loquare Admin', 'tester@loquare.com', '');

			echo json_encode(
				array(
					"status" => 200,
					"message" => "Congratulation! <br/> Property published successfuly!"
				)
			);

		}
		else{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}

		exit();

	}

	function publish(Request $request)
	{
		$property_id =$request->input('id');
		$status =$request->input('status');


		$filter=array( "id"=>$property_id );
		$data = array( "status" => $status );
		$table = new property();

		$property = common::get_single($table, $filter);

		if($property['verified'] == 1 || $status == 0)
		{
			$published = common::update_data($table, $data, $filter);

			if($published != false)
			{
				$status = ($status == 1)?"Publish":"Unpublish";
				$property = property::find($property_id);
				$log = array(
					"type" => strtolower($status."-property"),
					"message" => $status." property ".$property->direccion." "
				);
				activity::addlog($log);

				echo json_encode(
					array(
						"status" => 200,
						"message" => "Congratulation<br/>Your property published successfuly!"
					)
				);
			}else{
				echo json_encode(
					array(
						"status" => 500,
						"message" => "Sorry! Something went wrong<br/>Please try again!"
					)
				);
			}
		}
		else{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Your property is under review<br/>Please wait till your property being verified!"
				)
			);
		}

		exit();
	}

	function get_property($type="post", $id = "", Request $request)
	{
		if($id == "")
		{
			$id = $request->input("id");
		}


		$filter = array(
			array("PR.id", $id)
		);

		$data = property::get_property($filter);

		if($type = "json")
		{
			echo json_encode($data);
		}
		else{
			return $data;
		}
		exit();
	}

	function delete(Request $request)
	{
		$property_id =$request->input('id');

		$filter = array(
			array("id", "=",$property_id)
		);
		$table = new property();
		$property = common::get_single($table, $filter);
		$deleted = common::delete_data($table,$filter);

		if($deleted != false)
		{
			$log = array(
				"type" => "delete-property",
				"message" => "Deleted property ".$property['direccion']
			);
			activity::addlog($log);

			$filter = array(
				array("property", "=",$property_id)
			);
			$deleted_contact = common::delete_data(new contact(),$filter);

			$filter = array(
				array( "post_id", "=",$property_id )
			);
			$deleted_image = common::delete_data(new uploadfile(),$filter);

			if($deleted_contact != false)
			{
				echo json_encode(
					array(
						"status" => 200,
						"message" => "Congratulation! <br/> Property deleted successfuly!"
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

		}else{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}
		exit();
	}

	function edit($id = "", Request $request)
	{
		if($id != "") {

			$filter = array("id" => $id);
			$table = new property();
			$property = common::get_single($table, $filter);

			if($property != false)
			{
				$this->data['property'] = $property;

				$this->data['scripts'] = array(
					'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.min.js',
					'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js',
					asset('/frontend/js/add_property.js')
				);

				$this->data['styles'] = array(
					asset("/frontend/styles/add_property.css")
				);

				$filter = array(
    				array("parent", "=", 0)
    			);
		        $this->data['property_types'] = common::get_by_condition(new property_type(),$filter);

				$filter = array("property" => $id);
				$this->data['contact'] = common::get_single(new contact(), $filter);

				$filter = array(
					array("post_id", "=", $id),
					array("post_type", "=", "property-image")
				);
				$this->data['images'] = common::get_by_condition(new uploadfile(), $filter);
				
				if(!empty($this->data['images']))
				{
					foreach($this->data['images'] as $key=>$value)
					{
						$imagestore = asset('/storage/Property/'.$value['post_id'].'/'.$value['filename']);
						$this->data['images'][$key]['filename'] = Storage::disk('s3')->exists("Properties/".$property['id']."/".$value['filename'])?Storage::disk('s3')->url("Properties/".$property['id']."/".$value['filename']):$imagestore;
					}
				}

				$filter = array(
					array("post_id", "=", $id),
					array("post_type", "=", "owner-certificate")
				);
				$owner_certificate = common::get_by_condition(new uploadfile(), $filter);
				$this->data['owner_certificate'] = ($owner_certificate != false) ? $owner_certificate[0] : $owner_certificate;
				$imagestore = (!empty($this->data['owner_certificate']))?asset('/storage/ownercertificates/'.$this->data['owner_certificate']['post_id'].'/'.$this->data['owner_certificate']['filename']):'';
				$this->data['owner_certificate']['filename'] = Storage::disk('s3')->exists("Ownercertificates/".$property['id']."/".$this->data['owner_certificate']['filename'])?Storage::disk('s3')->url("Ownercertificates/".$property['id']."/".$this->data['owner_certificate']['filename']):$imagestore;
				
				$filter = array(
					array("post_id", "=", $id),
					array("post_type", "=", "energy-certificate")
				);
				$energy_certificate = common::get_by_condition(new uploadfile(), $filter);
				$this->data['energy_certificate'] = ($energy_certificate != false) ? $energy_certificate[0] : $energy_certificate;
				$imagestore =(!empty($this->data['energy_certificate']['filename']))? asset('/storage/enrgycertificats/'.$this->data['energy_certificate']['post_id'].'/'.$this->data['energy_certificate']['filename']):'';
				$this->data['energy_certificate']['filename'] = Storage::disk('s3')->exists("Energycertificates/".$property['id']."/".$this->data['energy_certificate']['filename'])?Storage::disk('s3')->url("Energycertificates/".$property['id']."/".$this->data['energy_certificate']['filename']):$imagestore;
				
				$this->data['districts'] = common::get_all(new District(), -1, array("dist_name" => "asc"));
				$filter = array(
					array("dist_id", "=", $property['dist_id'])
				);

				$this->data['hoods'] = common::get_by_condition(new Hoods(), $filter, array("hood" => "asc"));

				$this->data['commission'] = DB::table("price_history")->where("property", $id)->latest()->first();

				$log = array(
					"type" => "page",
					"message" => "Visit edit property page for property ".$property['direccion']
				);
				activity::addlog($log);

				$this->view = "edit_property";
				return $this->load_view($this->view);
			}
			else{

				return redirect("/");
			}
		}
		else{

			return redirect("/");
		}

	}

	function property_post($request)
	{
		$comunidad_autonoma = $request->input("comunidad_autonoma");
		$cops = $request->input("cops");
		$direccion = $request->input("direccion");
		$localidad = $request->input("localidad");
		$provincia = $request->input("provincia");
		$dist_id = $request->input("dist_id");
		$hood = $request->input("hood");
		$state_id = "Barcelona";
		$property_for = $request->input("property_for");
		$property_type = $request->input("property_type");
		$property_sub_type = $request->input("property_sub_type");
        $rooms = $request->input("rooms");
		$bathrooms = $request->input("bathrooms");
		$sizem2 = $request->input("sizem2");
		$property_deal = $request->input("property_deal");
		$rent_by = $request->input("rent_by");
		$lease_duration = $request->input("lease_duration");
		$price = ($property_deal == "SALE")?$request->input("price_sale"):$request->input("price_rent");
		$price = trim(implode("",explode(",", $price)));

		$guarantee = $request->input("guarantee");
		$guarantee = ($guarantee != '')?$guarantee:'0';
		$guarantee = trim(implode("",explode(",", $guarantee)));

		$discription = $request->input("discription");
		$usability = $request->input("usability");
        $favourite_space = $request->input("favourite_space");
		$about_hood = $request->input("about_hood");
		$construction = $request->input("construction");

		$elevetor = $request->input("elevetor");
		$doorman = $request->input("doorman");
		$furnished = $request->input("furnished");

        $price_help_needed = $request->input("price_help_needed");
		$description_help_needed = $request->input("description_help_needed");
		$images_help_needed = $request->input("images_help_needed");
		$documentation_help_needed = $request->input("documentation_help_needed");

		$furnished_kitchen = $request->input("furnished_kitchen");
		$furnished_all = $request->input("furnished_all");
		$floor = $request->input("floor");
		$floor_hardwood = $request->input("floor_hardwood");
		$floor_ceramic = $request->input("floor_ceramic");
		$floor_natural_light = $request->input("floor_natural_light");
		$cellings = $request->input("cellings");
		$cellings_high = $request->input("cellings_high");
		$cellings_other = $request->input("cellings_other");

		$heating = $request->input("heating");
		$laundry = $request->input("laundry");
		$central_ac = $request->input("central_ac");
		$outdoor_space = $request->input("outdoor_space");
		$gym = $request->input("gym");
		$dishwasher = $request->input("dishwasher");
		$pool = $request->input("pool");
		$pets = $request->input("pets");
		$cats = $request->input("cats");
		$others = $request->input("others");
		$latitude = $request->input("latitude");
		$longitude = $request->input("longitude");


		$published_by = $request->input("published_by");

		$status = 1;

		$type2  = 0;
		$cost_mortage = 0;
		$mortage_percentage = 0;
		$historical_price1 = 0;
		$historical_price2 = 0;
		$historical_price3 = 0;
		$plisted_d_historical_price3 = 0;
		$historical_price2_d_historical_price3 = 0;
		$plisted_d_historical_price1 = 0;
		$estimated_cost = 0;
		$mortage = 0;
		$closing_cost_mortage = 0;
		$potential_income = 0;

		if($property_deal == "SALE")
		{
			$type2  = 1;
			$cost_mortage = 1.5;
			$mortage_percentage = '30';

			$historical_price1  = $price - 5678;
			$historical_price2  = $historical_price1+4560;
			$historical_price3  = $historical_price2-1234;
			$plisted_d_historical_price3 = ( ( $price/$historical_price3 )-1 ) * 100;

			$historical_price2_d_historical_price3 = ( ( $historical_price2/$historical_price3 ) -1 ) * 100;
			$plisted_d_historical_price1=( ( $price/$historical_price1) - 1 ) * 100;

			$estimated_cost = $price * 0.115 * $type2;
			$mortage = ( $price * $mortage_percentage)/100;

			$closing_cost_mortage   = ($mortage * $cost_mortage)/100;
			$potential_income = $closing_cost_mortage + $estimated_cost;
		}

		$data = array(
			"comunidad_autonoma" => $comunidad_autonoma,
			"user_id"=>Auth::user()->id,
			"published_by" => $published_by,
			"cops" => $cops,
			"direccion" => $direccion,
			"localidad" => $localidad,
			"provincia" => $provincia,
			"property_for" => $property_for,
			"hoods" => $hood,
			"dist_id" => $dist_id,
			"state_id" => $state_id,
			"property_type" => $property_type,
			"property_sub_type" => $property_sub_type,
			"rooms" => $rooms,
			"bathrooms" => $bathrooms,
			"sizem2" => $sizem2,
			"property_deal" => $property_deal,
			"rent_by" => $rent_by,
			"lease_duration" => $lease_duration,
			"price" => $price,
			"guarantee" => $guarantee,
			"discription" => $discription,
			"usability" => (trim($usability) != "")?1:0,
            "favourite_space" => $favourite_space,
			"about_hood"    => $about_hood,
			"construction" => $construction,
			"elevetor" => $elevetor,
			"doorman" => $doorman,
			"furnished" => $furnished,
			"furnished_kitchen" => $furnished_kitchen,
			"furnished_all" => $furnished_all,
			"floor" => $floor,
			"floor_hardwood" => $floor_hardwood,
			"floor_ceramic" => $floor_ceramic,
			"floor_natural_light" => $floor_natural_light,
			"cellings" => $cellings,
			"cellings_high" => $cellings_high,
			"cellings_other" => $cellings_other,
			"heating" => $heating,
			"laundry" => $laundry,
			"central_ac" => $central_ac,
			"outdoor_space" => $outdoor_space,
			"gym" => $gym,
			"dishwasher" => $dishwasher,
			"pool" => $pool,
			"pets" => $pets,
			"cats" => $cats,
			"others" => $others,
			"latitude" => $latitude,
			"longitude" => $longitude,
			"status" => $status,
			"created_at" => date("Y-m-d h:i:s"),
			"historical_price1"=>$historical_price1,
			"historical_price_date1"=>'1/1/2018',
			"historical_price2"=>$historical_price2,
			"historical_price_date2"=>'11/17/2017',
			"historical_price3"=>$historical_price3,
			"historical_price_date3"=>'9/18/2017',
			"plisted_d_historical_price3"=> $plisted_d_historical_price3,
			"historical_price2_d_historical_price3" => $historical_price2_d_historical_price3,
			"plisted_d_historical_price1"=>$plisted_d_historical_price1,
			"type2"=>$type2,
			"estimated_cost"=>$estimated_cost,
			"mortage"=>$mortage,
			"mortage_percentage"=>$mortage_percentage,
			"closing_cost_mortage"=>$closing_cost_mortage,
			"cost_mortage"=>$cost_mortage,
			"potential_income"=>$potential_income,
            "price_help_needed" => $price_help_needed,
            "description_help_needed" => $description_help_needed,
            "images_help_needed" => $images_help_needed,
            "documentation_help_needed" => $documentation_help_needed,
		);


		return $data;
	}


	function update(Request $request)
	{
		$propertyId=$request->input("property_id");

		$data = $this->property_post($request);

		$table = new property();
		$filter=array("id"=>$propertyId);
		$property = common::get_single($table, $filter);

		$propertyUpdated = common::update_data($table, $data, $filter);

		if($propertyUpdated != false)
		{
			$log = array(
				"type" => "edit-property",
				"message" => "update property ".$property['direccion']
			);
			activity::addlog($log);

			if($data['price'] != $property['price'])
			{
				$price = array(
					"price" => $data['price'],
					"property" => $propertyId,
					"loquare_commission" => $request->input('loquare_commission'),
					"realestate_commission" => $request->input('realestate_commission'),
					"created_at" => date('Y-m-d H:i:s'),
					"updated_at" => date('Y-m-d H:i:s'),
				);
				$flag = DB::table("price_history")->insert($price);
			}

			$table = new uploadfile();

			$uploadfiles = $request->file('property_images');

			if($uploadfiles != "")
			{
				foreach($uploadfiles as $file)
				{
					$files = common::s3Fileupload($file, "Properties/".$propertyId, array("width" => 355));
					$data = array(
						"filename"   => $files['name'],
						"filetype"   => $files['type'],
						"post_id"    =>  $propertyId,
						"post_type"  => "property-image",
						"created_at" => date('Y-m-d H:i:s')
					);

					common::insert_data($table, $data);
				}
			}

			$uploadfiles = $request->file('energy_certificate');

			if($uploadfiles != "") {

				$files = common::s3Fileupload($uploadfiles, "Energycertificates/".$propertyId, array("width" => 355));
				$data = array(
					"filename"   => $files['name'],
					"filetype"   => $files['type'],
					"post_id"    =>  $propertyId,
					"post_type"  => "energy-certificate",
					"created_at" => date('Y-m-d H:i:s')
				);

				$filter=array("post_id"=>$propertyId,"post_type"  => "energy-certificate");
				$energy_certificate = common::get_single($table, $filter);
				if($energy_certificate != false)
				{
					common::update_data($table, $data ,$filter);
				}else
				{
					common::insert_data($table, $data);
				}
			}

			$uploadfiles = $request->file('owner_certificate');

			if($uploadfiles != "") {
				$files = common::s3Fileupload($uploadfiles, "Ownercertificates/".$propertyId, array("width" => 355));
				$data = array(
					"filename"   => $files['name'],
					"filetype"   => $files['type'],
					"post_id"    =>  $propertyId,
					"post_type"  => "owner-certificate",
					"created_at" => date('Y-m-d H:i:s')
				);

				$filter=array("post_id"=>$propertyId,"post_type"  => "owner-certificate");
				$energy_certificate = common::get_single($table, $filter);
				if($energy_certificate != false)
				{
					common::update_data($table, $data ,$filter);
				}else
				{
					common::insert_data($table, $data);
				}
			}

			$data = array(
				"property" => $propertyId,
				"contact_name" => $request->input('contact_name'),
				"contact_phone" => $request->input('contact_phone'),
				"contact_email" => $request->input('contact_email'),
				"duration" => $request->input('duration'),
				"created_at" => date('Y-m-d H:i:s')
			);

			$filter=array('property'=>$propertyId);
			common::update_data(new contact(), $data ,$filter);

			echo json_encode(
				array(
					"status" => 200,
					"message" => "Congratulation! <br/> Property updated successfuly!"
				)
			);
		}
		else{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}

		exit();
	}

	function nearbyproperty(Request $request)
	{
		$nearby         = $request->input("nearby");
		$property_deal   = $request->input("property_deal");
		$property       = $request->input("property");
		if($property != false)
		{
			$table = new property();
			$filter = array("id" => $property);
			$property = common::get_single($table, $filter);

			if($property != false)
			{

				$filter  = array(
					array("PR.id", "!=", $property['id'])
				);
				if($nearby != "")
				{
					$filter[] = array("PR.".$nearby, "=", $property[$nearby]);
				}

				$filter[] = array("PR.property_deal", "=", $property['property_deal']);
				$filter[] = array("PR.property_deal", "=", $property['property_deal']);


				$data = property::get_property($filter, 100000000000000000000, 0,$property);


				foreach($data as $key=>$value)
				{
					$imagestore = asset('/storage/Property/'.$property['id'].'/'.$value['filename']);
					$data[$key]['filename'] = Storage::disk('s3')->exists("Properties/".$property['id']."/".$value['filename'])?Storage::disk('s3')->url("Properties/".$property['id']."/".$value['filename']):$imagestore;
				}
				echo json_encode($data);
			}
			else{
				echo json_encode(FALSE);
			}
		}
		else{
			echo json_encode(FALSE);
		}

		exit();
	}

	function add_help(Request $request)
	{
		$fullname = $request->input("fullname");
		$phon_no = $request->input("phon_no");
		$email = $request->input("email");
		$message = $request->input("message");
		$created_at = date("Y-m-d H:i:s");

		$data = array(
			"fullname" => $fullname,
			"phon_no" => $phon_no,
			"email" => $email,
			"message" => $message,
			"created_at" => $created_at
		);

	    $table = new addHelp();
		$flag = common::insert_data($table, $data);

		/*
		$data['heading'] = ;
		Loquare::sendMail('AddpropertyHelp', $data, 'Loquare - Your offer status changed', ucfirst($fullname), $email, '');

		$data['heading'] = $fullname." has been requested for add property help. Contact details are as follow.";
		Loquare::sendMail('AddpropertyHelp', $data, 'Loquare - Your offer status changed', "Admin - Loquare", "tester@loquare.com", '');
	
		*/
		
		$recipients = [ ["name" => "Loquare", "email" => "tester@loquare.com","heading" => $fullname." has been requested for add property help. Contact details are as follow."], ["name" => $fullname, "email" => $email,"heading" => "You have requested for add property help. your details are as follow."] ];
		
		foreach($recipients as $recipient) {
			
			Mail::send('emails/AddpropertyHelp',array(
									"fullname" => $fullname,
									"phon_no" => $phon_no,
									"email" => $email,
									"heading" => $recipient['heading'],
									"body_message" => $message,
									"created_at" => $created_at
								), function($message) use ($recipient)
						   {
								$message->from('tester@loquare.com');
								$message->to($recipient['email'], $recipient['name'])->subject('Loquare: Get more info');
						   });
		}

		$log = array(
			"type" => "addproperty-help",
			"message" => "Request for add property help with name : ".$fullname." and email : ".$email
		);

		activity::addlog($log);

		echo ($flag != false)?json_encode(TRUE):json_encode(FALSE);

		exit();
	}

	function import(Request $request)
	{

		$response = array(
			"status" => 500,
			"message" => "Unknown server error!"
		);

		$uploadfiles = $request->file('property_excel');
		$published_by = $request->input("published_by");

		if($uploadfiles != "") {

			$files = common::s3Fileupload($uploadfiles, "ImportedXML/" . Auth::user()->id);

			if($files)
			{
				$company = new company();
				$company->company_name = $request->input("company_name");
				$company->company_email = $request->input("company_email");
				$company->company_phone = $request->input("company_phone");
				$company->company_website = $request->input("company_website");
				$company->company_address = $request->input("company_address");
				$company->userid = Auth::user()->id;
				$company->created_at = date("Y-m-d H:i:s");

				if($company->save())
				{
					$table = new uploadfile();
					$company = $company->id;
					$company_image = common::s3Fileupload($request->file("company_logo"), "company/" . $company, array("width" => 300, "height" => 300));

					$company_image = array(
						"filename" => $company_image['name'],
						"filetype" => $company_image['type'],
						"post_id" => $company,
						"post_type" => "company-logo",
						"created_at" => date('Y-m-d H:i:s')
					);

					common::insert_data($table, $company_image);

					$data = array(
						"filename" => $files['name'],
						"filetype" => $files['type'],
						"post_id" => Auth::user()->id,
						"post_type" => "company-xml",
						"created_at" => date('Y-m-d H:i:s')
					);

					$xmluploaded = common::insert_data($table, $data);

					if($xmluploaded != false)
					{
						$xmlfile = file_get_contents(Storage::disk('s3')->url('ImportedXML/'.Auth::user()->id."/".$files['name']));

						$ob = simplexml_load_string($xmlfile);
						$ob = json_encode($ob);
						$configData = json_decode($ob, true);

						$configData = (array_key_exists("comunidad_autonoma",$configData['property']))?$configData:$configData['property'];


						foreach($configData as $property)
						{

							$features = $property['features'];
							$images = $property['images']['image'];
							$certificates = $property['certificates'];
							unset($property['features']);
							unset($property['images']);
							unset($property['certificates']);
							$property = array_merge($property, $features);

							$property['user_id'] = Auth::user()->id;
							$property['company_id'] = $company;
							$property['published_by'] = $published_by;
							$property['property_type'] = common::id_from_table($property['property_type'], "property_types", "property_type_name");
							$property['property_sub_type'] = common::id_from_table($property['property_sub_type'], "property_types", "property_type_name");
							$property['dist_id'] = common::id_from_table($property['district'], "district", "dist_name");
							$property['state_id']  = $property['state'];

							unset($property['district']);
							unset($property['state']);

							$property['hoods'] = common::id_from_table($property['hoods'], "hoods", "hood");
							$property['usability'] = ($property['usability'] == "USED" || $property['usability'] == "YES" || $property['usability'] == 1)?1:0;
							$property['created'] = date("Y-m-d H:i:s");
							$property['documentation_help_needed'] = ($property['documentation_help_needed'] == "" || $property['documentation_help_needed'] == NULL || $property['documentation_help_needed'] == "NULL" || $property['documentation_help_needed'] == "NO" )?0:1;
							$property['description_help_needed'] = ($property['description_help_needed'] == "" || $property['description_help_needed'] == NULL || $property['description_help_needed'] == "NULL"  || $property['description_help_needed'] == "NO" )?0:1;
							$property['images_help_needed'] = ($property['images_help_needed'] == "" || $property['images_help_needed'] == NULL || $property['images_help_needed'] == "NULL"  || $property['images_help_needed'] == "NO" )?0:1;
							$property['price_help_needed'] = 0;

							$property['historical_price2_d_historical_price3'] = 0;
							$property['plisted_d_historical_price3'] = 0;
							$property['plisted_d_historical_price1'] = 0;
							$property['historical_price1'] = 0;
							$property['historical_price2'] = 0;
							$property['historical_price3'] = 0;
							$property['potential_income'] = 0;

							$property['estimated_cost'] = 0;
							$property['mortage'] = 0;
							$property['mortage_percentage'] = 0;
							$property['closing_cost_mortage'] = 0;
							$property['cost_mortage'] = 0;

							$property['historical_price_date1'] = '';
							$property['historical_price_date2'] = '';
							$property['historical_price_date3'] = '';


							if($property['property_deal'] == "SALE")
							{
								$type2  = 1;
								$cost_mortage = 1.5;
								$mortage_percentage = '30';

								$price = $property['price'];

								$historical_price1  = $price - 5678;
								$historical_price2  = $historical_price1+4560;
								$historical_price3  = $historical_price2-1234;
								$plisted_d_historical_price3 = ( ( $price/$historical_price3 )-1 ) * 100;
								$historical_price2_d_historical_price3 = ( ( $historical_price2/$historical_price3 ) -1 ) * 100;
								$plisted_d_historical_price1=( ( $price/$historical_price1) - 1 ) * 100;

								$estimated_cost = $price * 0.115 * $type2;
								$mortage = ( $price * $mortage_percentage)/100;

								$closing_cost_mortage   = ($mortage * $cost_mortage)/100;
								$potential_income = $closing_cost_mortage + $estimated_cost;

								$property['plisted_d_historical_price3'] = $plisted_d_historical_price3;
								$property['historical_price2_d_historical_price3'] = $historical_price2_d_historical_price3;
								$property['plisted_d_historical_price1'] = $plisted_d_historical_price1;

								$property['historical_price_date1'] = '1/1/2018';
								$property['historical_price_date2'] = '11/17/2017';
								$property['historical_price_date3'] = '9/18/2017';

								$property['historical_price1'] = $historical_price1;
								$property['historical_price2'] = $historical_price2;
								$property['historical_price3'] = $historical_price3;

								$property['potential_income'] = $potential_income;

								$property['estimated_cost'] = $estimated_cost;
								$property['mortage'] = $mortage;
								$property['mortage_percentage'] = $mortage_percentage;
								$property['closing_cost_mortage'] = $closing_cost_mortage;
								$property['cost_mortage'] = $cost_mortage;
							}

							$property['elevetor'] = ($property['elevetor'] == "" || $property['elevetor'] == NULL || $property['elevetor'] == "NO" || $property['elevetor'] == "NULL" )?0:1;
							$property['doorman'] = ($property['doorman'] == "" || $property['doorman'] == NULL || $property['doorman'] == "NO" || $property['doorman'] == "NULL")?0:1;
							$property['furnished'] = ($property['furnished'] == "" || $property['furnished'] == NULL || $property['furnished'] == "NO" || $property['furnished'] == "NULL" )?0:1;
							$property['furnished_kitchen'] = ($property['furnished_kitchen'] == "" || $property['furnished_kitchen'] == NULL || $property['furnished_kitchen'] == "NO"  || $property['furnished_kitchen'] == "NULL")?0:1;
							$property['furnished_all'] = ($property['furnished_all'] == "" || $property['furnished_all'] == NULL || $property['furnished_all'] == "NO" || $property['furnished_all'] == "NULL" )?0:1;
							$property['floor'] = ($property['floor'] == "" || $property['floor'] == NULL || $property['floor'] == "NO" || $property['floor'] == "NULL" )?0:1;
							$property['floor_hardwood'] = ($property['floor_hardwood'] == "" || $property['floor_hardwood'] == NULL || $property['floor_hardwood'] == "NO" || $property['floor_hardwood'] == "NULL" )?0:1;
							$property['floor_ceramic'] = ($property['floor_ceramic'] == "" || $property['floor_ceramic'] == NULL || $property['floor_ceramic'] == "NO" || $property['floor_ceramic'] == "NULL" )?0:1;
							$property['floor_natural_light'] = ($property['floor_natural_light'] == "" || $property['floor_natural_light'] == NULL || $property['floor_natural_light'] == "NO" || $property['floor_natural_light'] == "NULL" )?0:1;
							$property['cellings'] = ($property['cellings'] == "" || $property['cellings'] == NULL || $property['cellings'] == "NO" || $property['cellings'] == "NULL" )?0:1;
							$property['cellings_high'] = ($property['cellings_high'] == "" || $property['cellings_high'] == NULL || $property['cellings_high'] == "NO" || $property['cellings_high'] == "NULL" )?0:1;
							$property['cellings_other'] = ($property['cellings_other'] == "" || $property['cellings_other'] == NULL || $property['cellings_other'] == "NO" || $property['cellings_other'] == "NULL" )?0:1;
							$property['heating'] = ($property['heating'] == "" || $property['heating'] == NULL || $property['heating'] == "NO" || $property['heating'] == "NULL" )?0:1;
							$property['laundry'] = ($property['laundry'] == "" || $property['laundry'] == NULL || $property['laundry'] == "NO" || $property['laundry'] == "NULL" )?0:1;
							$property['central_ac'] = ($property['central_ac'] == "" || $property['central_ac'] == NULL || $property['central_ac'] == "NO" || $property['central_ac'] == "NULL" )?0:1;
							$property['outdoor_space'] = ($property['outdoor_space'] == "" || $property['outdoor_space'] == NULL || $property['outdoor_space'] == "NO" || $property['outdoor_space'] == "NULL" )?0:1;
							$property['gym'] = ($property['gym'] == "" || $property['gym'] == NULL || $property['gym'] == "NO" || $property['gym'] == "NULL" )?0:1;
							$property['dishwasher'] = ($property['dishwasher'] == "" || $property['dishwasher'] == NULL || $property['dishwasher'] == "NO" || $property['elevetor'] == "NULL" )?0:1;
							$property['pool'] = ($property['pool'] == "" || $property['pool'] == NULL || $property['pool'] == "NO" || $property['pool'] == "NULL" )?0:1;
							$property['pets'] = ($property['pets'] == "" || $property['pets'] == NULL || $property['pets'] == "NO" || $property['pets'] == "NULL" )?0:1;
							$property['dogs'] = ($property['dogs'] == "" || $property['dogs'] == NULL || $property['dogs'] == "NO" || $property['dogs'] == "NULL" )?0:1;
							$property['cats'] = ($property['cats'] == "" || $property['cats'] == NULL || $property['cats'] == "NO" || $property['cats'] == "NULL" )?0:1;
							$property['most_relevant'] = ($property['most_relevant'] == "" || $property['most_relevant'] == NULL || $property['most_relevant'] == "NO" || $property['most_relevant'] == "NULL" )?0:1;
							$property['loquare_listing'] = ($property['loquare_listing'] == "" || $property['loquare_listing'] == NULL || $property['loquare_listing'] == "NO" || $property['loquare_listing'] == "NULL" )?0:1;
							$property['others'] = ($property['others'] == "" || $property['others'] == NULL || $property['others'] == "NO" || $property['others'] == "NULL" )?0:1;
							$property['offered'] = ($property['offered'] == "" || $property['offered'] == NULL || $property['offered'] == "NO" || $property['offered'] == "NULL" )?0:$property['offered'];

							$table = new property();

							$propertyid = common::insert_data($table, $property);

							if($propertyid != false)
							{
								$sale_price = $property['price'];

								$commission_realestate = number_format((($sale_price * 3)/100),2);
								$commission_loquare = (($sale_price * 1.5)/100);
								$commission_loquare = number_format(($sale_price <= 400000)?((($sale_price/400000)*(1.5/100))*$sale_price):$commission_loquare,2);

								$price = array(
									"price" => $property['price'],
									"property" => $propertyid,
									"loquare_commission" => $commission_loquare,
									"realestate_commission" => $commission_realestate,
									"created_at" => date('Y-m-d H:i:s'),
									"updated_at" => date('Y-m-d H:i:s')
								);

								DB::table("price_history")->insert($price);

								$table = new uploadfile();
								foreach($images as $image){
									$image = common::xmlImageUpload($image,"Properties/".$propertyid, array("width" => 355));

									if($image)
									{
										$data = array(
											"filename"  => $image['name'],
											"filetype"  => $image['type'],
											"post_id"   => $propertyid,
											"post_type" => "property-image",
											"created_at" => date('Y-m-d H:i:s')
										);

										common::insert_data($table, $data);
									}

								}

								if($certificates['energycertificate'] != "")
								{
									$energycertificate = common::xmlImageUpload($certificates['energycertificate'],"Energycertificates/".$propertyid, array("width" => 355));

									if($energycertificate)
									{
										$data = array(
											"filename"  => $energycertificate['name'],
											"filetype"  => $energycertificate['type'],
											"post_id"   => $propertyid,
											"post_type" => "energy-certificate",
											"created_at" => date('Y-m-d H:i:s')
										);

										common::insert_data($table, $data);
									}
								}

								if($certificates['ownercertificate'] != "")
								{
									$ownercertificate = common::xmlImageUpload($certificates['ownercertificate'],"Ownercertificates/".$propertyid, array("width" => 355));

									if($ownercertificate)
									{
										$data = array(
											"filename"  => $ownercertificate['name'],
											"filetype"  => $ownercertificate['type'],
											"post_id"   => $propertyid,
											"post_type" => "owner-certificate",
											"created_at" => date('Y-m-d H:i:s')
										);

										common::insert_data($table, $data);
									}
								}

								$response = array(
									"status" => 200,
									"message" => "Congratulation! <br/> Property published successfuly!"
								);
							}
							else{

								$response = array(
									"status" => 500,
									"message" => "Sorry! Something went wrong<br/>Please try again!"
								);
								break;
								die;
							}

						}


					}

				}
				else{

					$response = array(
						"status" => 500,
						"message" => "Sorry! Something went wrong<br/>Please try again!"
					);

					die;
				}

			}
		}
		echo json_encode($response);

		exit();
	}

	function demoexcel()
	{
		$file= storage_path('/upload.xml');

		$headers = array(
			'Content-Type: text/xml',
		);

		return Response::download($file, 'demo_xml.xml', $headers);
	}

	function get_average_price($id="", Request $request)
	{
		if($id == "")
		{
			$id = $request->input("property");
		}

		if($id != "")
		{
			$property = new property();
			$property = $property->find($id);
			if($property) {
				$prices = PriceHistoryModel::select(DB::raw('
					DATE_FORMAT(price_history.created_at,"%y-%m") AS monthlabel,
					ROUND(AVG(price_history.price)) as average_price 
				'))
					->leftjoin("property", "property.id", "=", "price_history.property")
					->where('property.cops', $property->cops)
					->where('price_history.created_at', '<=', date('Y-m-d 23:59:59'))
					->orderBy('monthlabel', 'ASC')
					->groupBy('monthlabel')
					->get();

				echo json_encode($prices);
			}
			else{
				echo json_encode(FALSE);
			}
		}

		exit();
	}

	public function compare_property(Request $request)
	{
		$property = $request->input("property");
		$distance = $request->input("distance");

		$property = PropertyModel::find($property);


		if($property)
		{
			$sold_properties = PropertyModel::select("*")->addSelect(DB::raw('
				(
				6371 * acos (
			      cos ( radians(' . $property->longitude. ') )
			      * cos( radians( longitude ) )
			      * cos( radians( latitude ) - radians(' . $property->latitude. ') )
			      + sin ( radians(' . $property->longitude. ') )
			      * sin( radians( longitude ) )
			    )
			  ) AS distance'))
				->whereHas("offers",function($query){
					$query->where("sold_status", 1);
				})
				->where("property_deal", $property->property_deal)
				->where("id", "!=", $property);

			if($distance != "")
			{
				$distance = ($distance/1000);
				$sold_properties->havingRaw("distance <= ".$distance);
			}

			$data['sold_properties'] = $sold_properties->orderByRaw("distance ASC")->get();
			$compare = View::make('property.compare', $data)->render();
			
			echo json_encode($compare);

		}else
		{
			echo json_encode(FALSE);
		}
		exit();
	}

	function deleteimage(Request $request)
	{
		$id = $request->input("image");
		$image = UploadModel::find($id);

		if($image)
		{
			echo ($image->delete())?json_encode(TRUE):json_encode(FALSE);
		}
		else{
			echo json_encode(FALSE);
		}
		exit();
	}
}
