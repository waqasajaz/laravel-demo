<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SchedulsModel;
use Illuminate\Http\Request;
use Auth;
use Input;
use Redirect;
use Excel;
use App\Offer;
use App\AdminUser;
use App\properties\PropertyModel as property;
use Crypt;
use Loquare;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CI_ModelController as common;
use App\UploadModel as uploads;

class AssetsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->property = new property();
        $this->objAdminUser = new AdminUser();
    }

    public function index($type = 'sale')
    {
        if(Auth::guard('admin')->user()->role->type != 'admin') {
            return redirect('/admin/dashboard');
        }
        $agents = AdminUser::where('role_id', 2)->get();
        $assets = $this->property
	        ->select(DB::raw('property.*, HO.hood, DT.dist_name,PC.contact_name, PC.contact_phone, PC.contact_email'))
	        ->leftjoin("hoods as HO", "HO.id", "=", "property.hoods")
	        ->leftjoin("district as DT", "DT.id", "=", "property.dist_id")
	        ->leftjoin("property_contact as PC", "PC.property", "=", "property.id")
            ->where('deleted', 0)
	        ->where('property_deal', $type)
	        ->where("published_by", "!=", "COMPANY")
	        ->orderBy('admin_notified', 'ASC')->paginate(10);

        return view('admin.assets', compact('agents', 'assets', 'type'));
    }

    public function published($type = 'sale')
    {
        if(Auth::guard('admin')->user()->role->type != 'admin') {
            return redirect('/admin/dashboard');
        }
        $agents = AdminUser::where('role_id', 2)->get();
        $assets = $this->property
	        ->where('deleted', 0)
	        ->where('property_deal', $type)
	        ->where('status', 1)
	        ->where("published_by","!=","COMPANY")
	        ->orderBy('property.id', 'DESC')->paginate(10);

	    $scripts = array(
		    asset('/backend/js/propertyimage_popup_slider.js')
	    );
        return view('admin.published-assets', compact('agents', 'assets', 'type', 'scripts'));
    }

	public function unpublished($type = 'sale')
	{
		if(Auth::guard('admin')->user()->role->type != 'admin') {
			return redirect('/admin/dashboard');
		}
		$agents = AdminUser::where('role_id', 2)->get();
		$assets = $this->property
			->where('deleted', 0)
			->where('property_deal', $type)
			->where('status', 0)
			->where("published_by","!=","COMPANY")
			->orderBy('property.id', 'DESC')->paginate(10);

		$scripts = array(
			asset('/backend/js/propertyimage_popup_slider.js')
		);

		return view('admin.unpublished-assets', compact('agents', 'assets', 'type', 'scripts'));
	}

    public function allocateAgent(Request $request)
    {
        $requestData = $request->all();
        property::where('id', $requestData['asset_id'])->update(['agent_id' => $requestData['agent_id'], 'admin_notified' => 1]);
        $agent = AdminUser::find($requestData['agent_id']);
        $MailContent['name'] = $agent->name;
        $MailContent['url'] = url('admin/my-assets');
        Loquare::sendMail('AgentAssigendNotification', $MailContent, 'New Asset Assigned', $agent->name, $agent->email, '');

        return response()->json([]);
    }

    public function myAssets($type = 'sale')
    {
        if(Auth::guard('admin')->user()->role->type != 'agent') {
            return redirect('/admin/dashboard');
        }
        $assets = property::where('agent_id', Auth::guard('admin')->user()->id)->where('deleted', 0)->where('property_deal', $type)->orderBy('agent_assigned_date', 'DESC')->get();
        return view('admin.my-assets', compact('assets', 'type'));
    }

    public function verify($property = "")
    {
		if($property != "")
		{
			$property = $this->property->find($property);
			if($property)
			{
				$property->admin_notified = 0;
				$property->status = 1;
				$property->verified = 1;
				$property->save();
				Session::flash("success", "Property varified!");
			}
			else{
				Session::flash("error", "ERROR! While Processing!");
			}
		}
	    return redirect()->back();
    }

    public function unverify($property = "")
    {
	    if($property != "")
	    {
		    $property = $this->property->find($property);
		    if($property)
		    {
			    $property->verified = 0;
			    $property->status = 0;
			    $property->save();
			    Session::flash("success", "Property unverified!");
		    }
		    else{
			    Session::flash("error", "ERROR! While Processing!");
		    }
	    }
	    return redirect()->back();
    }

	public function gallery($property = "")
	{
		$property = $this->property->find($property);

		$s3 = Storage::disk("s3");
		return view("admin.gallery", compact('property', 's3'));
	}

	public function soldassets($type="")
	{
		$offers = Offer::where("sold_status", 1);

		if($type != "")
		{
			$offers = $offers->whereHas("property", function($query) use ($type){
				$query->where("property_deal", $type);
			});
		}

		$offers = $offers->paginate(10);

		return view("admin/soldAssets", array("offers" => $offers));
	}

	public function schedule_visit($id = "")
	{
		return redirect("admin");
		$property = new property();
		$property = $property->find($id);

		if($property)
		{
			return view("admin/schedule_visit", array("property" => $property));
		}
		else{
			return redirect("admin");
		}
	}

	public function schedule(Request $request)
	{
		$dates = $request->input("schedule");
		$scheduled_dates = explode(",", $dates);
		$property = $request->input("property");

		$table = new SchedulsModel();

		$table->whereNotIn("property",$scheduled_dates)
			->where("property", $property)
			->delete();

		foreach($scheduled_dates as $date)
		{
			$data = array(
				"property" => $property,
				"scheduled_dates" => $date
			);

			$exists = $table->where($data)->get();
			if($exists->count() <= 0)
			{
				$id = $table->create($data);
			}
		}

		Session::flash("success", "Property schedule for visit set successfuly!");

		return redirect('schedule_visit/'.$property);
	}

	public function edit_image(Request $request)
	{
		$propertyid = $request->input("property");
		$uploadfiles = $request->file('property_image');
		$image = $request->input("image_id");
		if($uploadfiles != "") {

			$files = common::s3Fileupload($uploadfiles, "Properties/".$propertyid, array("width" => 355));



			$image = uploads::find($image);


			$image->filename = $files['name'];
			$image->filetype = $files['type'];
			$image->post_type = "property-image";
			$image->updated_at = date('Y-m-d H:i:s');
			$image->save();

			return Redirect::back();
		}
	}
}
