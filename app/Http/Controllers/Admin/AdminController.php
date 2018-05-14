<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use Illuminate\Http\Request;
use Auth;
use Input;
use Redirect;
use Excel;
use App\AdminUser;
use Crypt;
use App\Http\Controllers\CI_ModelController as common;
use App\LogesModel as activity;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\Hash;
use App\CompanyModel as company;
use App\properties\PropertyModel as property;
use App\UploadModel as uploads;
use Loquare;
use App\UploadModel as uploadfile;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	protected  $data, $view, $scripts, $styles;
	public function __construct()
	{
		$this->middleware('auth.admin');
		$this->objAdminUser = new AdminUser();
		$this->data = array();
	}

	function load_view()
	{
		$this->data['logedin'] = Auth::guard('admin')->user();
		$this->data['scripts'] = $this->scripts;
		$this->data['styles'] = $this->styles;
		$this->data['success'] = session("success");
		$this->data['error'] = session("error");
		return view("admin.".$this->view, $this->data);
	}

	function settings()
	{
		if(Auth::guard('admin')->user()->role->type != 'admin') {
			return redirect('/admin');
		}
		$this->view = "settings";
		return $this->load_view();
	}

	function resetlogpassword(Request $request)
	{
		$password = $request->input("password");
		$new_password = $request->input("new_password");

		if($password != "")
		{
			$data = DB::table("authentications");
			$data = $data->where("password", md5($password));
			$data = $data->where("auth_for", "logs");
			$data = $data->get();

			$data = json_decode(json_encode($data));

			if(count($data) > 0)
			{
				$data = $data[0];
				$update = array("password" => md5($new_password));
				$flag = DB::table("authentications");
				$flag = $flag->where("id", $data->id);
				$flag = $flag->update($update);

				if($flag)
				{
					Session::flash("success", "Password for log access reset successfuly");
				}
				else{
					Session::flash("error", "ERROR While reseting password");
				}
			}else{
				Session::flash("error", "Invalid Athentication");
			}

		}
		else{
			Session::flash("error", "Log Athentication password required");
		}
		return redirect("admin/settings");
	}

	function resetpassword(Request $request)
	{
		$password = $request->input("password");
		$new_password = $request->input("new_password");

		$admin = Auth::guard('admin')->user();
		$current_password = $admin->password;

		if($password != "")
		{
			if(Hash::check($password, $current_password))
			{
				$admin->password = Hash::make($new_password);
				$flag = $admin->save();

				if($flag)
				{
					Session::flash("success", "Password for log access reset successfuly");
				}
				else{
					Session::flash("error", "ERROR While reseting password");
				}
			}else{
				Session::flash("error", "Invalid Athentication");
			}

		}
		else{
			Session::flash("error", "Log Athentication password required");
		}
		return redirect("admin/settings");
	}

	function companies()
	{
		$this->view = "companies";

		$filters = "";

		if(Auth::guard('admin')->user()->role_id != 1)
		{
			$filters = array(
				array("companies.agent_id", Auth::guard('admin')->user()->id)
			);
		}

		$this->data['companies'] = company::companies($filters);
		$this->data['agents'] = AdminUser::where('role_id', 2)
			->orderBy("name","ASC")
			->get();

		return $this->load_view();
	}

	function company_properties($id = "", $type="")
	{
		if($id != "")
		{
			$company = company::find($id);

			if($company)
			{
				$company = DB::table("companies")->select(DB::raw("companies.*, COUNT(property.company_id) as total"))
					->join("property", "property.company_id", "=", "companies.id")
					->where("property.company_id", $id)
					->groupBy("property.company_id")
					->get();

				$company = $company[0];
				$company->logo = uploads::where("post_type","company-logo")->where("post_id", $company->id)->get()->toArray();

				$this->data['company'] = $company;

				$filter = array(array("PR.company_id", $id));
				if($type == "published")
				{
					$filter[] = array("PR.status", 1);
				}
				if($type == "unpublished")
				{
					$filter[] = array("PR.status", 0);
				}
				$this->data['publish_status'] = $type;

				$this->data['properties'] =  property::properties($filter);

				$this->data['agents'] = AdminUser::where('role_id', 2)
										->orderBy("name","ASC")
										->get();

				$this->scripts = array(
					asset('/backend/js/propertyimage_popup_slider.js')
				);

				$this->view = "company_properties";
				return $this->load_view();
			}
			else{
				return redirect("admin/dashboard");
			}
		}
		else{
			return redirect("admin/dashboard");
		}

		$company = company::find($id);
	}

	function company_assign_agent(Request $request){

		$agent = $request->input("agent");
		$company = $request->input('company');

		$table = new company();

		$filter = array(
			array("id", "=", $company)
		);
		$data = array(
			"agent_id" => $agent
		);

		$flag = common::update_data($table, $data, $filter);

		if($flag != false)
		{
			$filter = array(
				array("company_id", "=", $company)
			);
			$flag = common::update_data(new property(), $data, $filter);
		}


		echo json_encode($flag);

		exit();

	}

	function importcompany(Request $request)
	{
		$admin = Auth::guard('admin')->user();

		$uploadfiles = $request->file('property_excel');
		$published_by = $request->input("published_by");

		if($uploadfiles != "") {

			$files = common::s3Fileupload($uploadfiles, "ImportedXML/" . $admin->id);

			if($files)
			{
				$company = new company();
				$company->company_name = $request->input("company_name");
				$company->company_email = $request->input("company_email");
				$company->company_phone = $request->input("company_phone");
				$company->company_website = $request->input("company_website");
				$company->company_address = $request->input("company_address");
				$company->userid = $admin->id;
				$company->created_at = date("Y-m-d H:i:s");

				if($company->save())
				{
					$table = new uploadfile();
					$company = $company->id;
					$company_image = common::s3Fileupload($request->file("company_logo"), "company/" . $company, array("width" => 100, "height" => 100));

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
						$xmlfile = file_get_contents(Storage::disk('s3')->url('ImportedXML/'.$admin->id."/".$files['name']));

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

							$property['user_id'] = $admin->id;
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
									$image = common::xmlImageUpload($image,"Properties/".$propertyid, array("width" => 300, "height" => 200));

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
									$energycertificate = common::xmlImageUpload($certificates['energycertificate'],"Energycertificates/".$propertyid, array("width" => 300, "height" => 200));

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
									$ownercertificate = common::xmlImageUpload($certificates['ownercertificate'],"Ownercertificates/".$propertyid, array("width" => 300, "height" => 200));

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


									Session::flash("success", "Congratulation! <br/> Property published successfuly!");
							}
							else{
								Session::flash("error", "Sorry! Something went wrong<br/>Please try again!");
								break;
								die;
							}

						}

					}

				}
				else{
					Session::flash("error", "Sorry! Something went wrong<br/>Please try again!");
					die;
				}

			}
		}

		return redirect("admin/companies");
	}

	function test()
	{
		$images = DB::table("certificates")->get()->toArray();

		if(count($images) > 0)
		{
			foreach($images as $image)
			{
				$filter = array(
					"post_type" => $image->post_type,
					"post_id"   => $image->post_id
				);
				$table = new uploads();

				$exists = common::get_single($table, $filter);

				if($exists != false)
				{
					$data = array(
						"filename" => $image->filename
					);
					$flag = common::update_data($table, $data, $filter);

				}else{
					$data = array(
						"filename"  => $image->filename,
						"filetype"  => $image->filetype,
						"post_id"   => $image->post_id,
						"post_type" => $image->post_type,
						"created_at" => date('Y-m-d H:i:s')
					);

					common::insert_data($table, $data);
				}

			}
		}
	}

	function test2()
	{
		$this->view = "test";
		return $this->load_view();
	}

	function certi(Request $request)
	{
		$result = 0;
		$i = $request->input("certi");
		$uploadfor = $request->input("uploadfor");

		$category = array(
			"energy" => array(
				"image" => "enrgycertificats/1/2017111312350781.png",
				"folder" => "Energycertificates",
				"post_type" => "energy-certificate"
			),
			"owner" => array(
				"image" => "ownercertificates/1/20171113123507864.png",
				"folder" => "Ownercertificates",
				"post_type" => "owner-certificate"
			)
		);


		$table = new uploads();
		$energy = storage_path($category[$uploadfor]['image']);

		{
			Storage::disk('s3')->deleteDirectory($category[$uploadfor]['folder'].'/'.$i);

			$image = common::xmlImageUpload($energy,$category[$uploadfor]['folder']."/".$i, array("width" => 355));

			if($image)
			{
				$data = array(
					"filename"  => $image['name'],
					"filetype"  => $image['type'],
					"post_id"   => $i,
					"post_type" => $category[$uploadfor]['post_type'],
					"created_at" => date('Y-m-d H:i:s')
				);

				$exists = $table->where("post_id", $i)
					->where("post_type", $category[$uploadfor]['post_type'])->get()->toArray();

				if(is_array($exists) && count($exists) > 0)
				{
					$exists = $exists[0];
					$filter = array( "id" => $exists['id']);
					$flag = common::update_data($table, $data, $filter);

					$result = $exists['id'];
				}
				else{
					$insert = common::insert_data($table, $data);
					$result = $insert;
				}
			}
		}
		echo $result;
		exit();
	}

}
