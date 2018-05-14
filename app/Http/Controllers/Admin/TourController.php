<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Input;
use Redirect;
use Excel;
use App\Chat;
use App\Tour;
use App\AdminUser;
use App\properties\PropertyModel as property;
use Crypt;
use Loquare;
use Illuminate\Support\Facades\Session;

 
class TourController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->tour = new Tour();
    }

    public function index($type="")
    {
	    $adminDetail = AdminUser::find(Auth::guard('admin')->user()->id);
        $adminRole = $adminDetail->role->type;

        $status = "all";
        $type=$type;

        $from = 'dashboard';
        return view('admin.tour_request', compact('adminRole', 'status','type'));
    }

    public function offers_completed($type = 'sale')
    {
        $adminDetail = AdminUser::find(Auth::guard('admin')->user()->id);
        $adminRole = $adminDetail->role->type;

        $actionColumn = 1;
        $status = "completed";

        $from = 'completed';
        return view('admin.dashboard', compact('adminRole', 'actionColumn', 'status', 'type', 'from'));
    }

    public function offers_accepted($type = 'sale')
    {
        $adminDetail = AdminUser::find(Auth::guard('admin')->user()->id);
        $adminRole = $adminDetail->role->type;

        $filters = [];
        if(Auth::guard('admin')->user()->role->type == 'agent'){
            $agentAssets = property::where('property_deal', $type)->where('agent_id', Auth::guard('admin')->user()->id)->pluck('id');
            if(count($agentAssets) > 0) {
                $filters['asset_id'] = $agentAssets->toArray();
            } else {
                $filters['asset_id'] = [0];
            }
        }

        $filters['step_7_completed'] = 1;
        $filters['accept_status'] = 1;
        $filters['property_deal'] = $type;

        $offers = $this->offer->getAll($filters);
        $actionColumn = 0;
        $status = "accepted";

        return view('admin.dashboard', compact('offers','adminRole', 'actionColumn', 'accepted', 'type', 'status'));
    }

    public function get_requested_tour(Request $request)
    {
	    $type = $request->input("type");
		$total = $request->input("total");

	    $filters = $type != ""?["visited" => $type]:[];

	    $tours = new Tour();

	    if(Auth::guard('admin')->user()->role->type == 'agent'){
		    $tours = $tours->whereHas("property", function($query){
			    $query->where("agent_id", Auth::guard('admin')->user()->id);
		    });
	    }

	    if(isset($filters) && !empty($filters)) {
		    if(isset($filters['visited'])){
			    $tours = $tours->where('visited', $filters['visited']);
		    }
	    }
	    if($filters['visited']=="yes"){
		    $tours = $tours->orderBy('updated_at', 'DESC');
	    }else{
		    $tours = $tours->orderBy('id', 'DESC');
	    }

	    $tours = $tours->get();

	    $count = $tours->count();

	    if($total != "")
	    {
			$response = ($total == $count)?FALSE:TRUE;
		    echo json_encode($response);
		    exit();
	    }
		else{

			$actionColumn = 1;

			$response =array(
				"total" => $count,
				"result" => View::make("admin.list-requested_tour", array(
					"tours" => $tours,
					"type"  => $type,
					"actionColumn" => $actionColumn
				))->render()
			);

			echo json_encode($response);
			exit();
		}

    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('/admin');
    }

    public function exportOffer($type, $status, $asset_type = "")
    {
        $filters = [];
        if(Auth::guard('admin')->user()->role->type == 'agent'){
            $agentAssets = property::where('agent_id', Auth::guard('admin')->user()->id)->pluck('id');
            if(count($agentAssets) > 0) {
                $filters['asset_id'] = $agentAssets->toArray();
            } else {
                $filters['asset_id'] = [0];
            }
        }

        if($status == 'completed') {
            $filename = 'offers_completed';
            $filters['step_7_completed'] = 1;
            $filters['accept_status'] = 0;
            $filters['property_deal'] = $asset_type;
        } else if ($status == 'accepted') {
            $filename = 'offers_accepted';
            $filters['step_7_completed'] = 1;
            $filters['accept_status'] = 1;
            $filters['property_deal'] = $asset_type;
        } else {
            $filename = 'offers_received';
            $filters['accept_status'] = 0;
        }

        $offers = $this->offer->getAll($filters);
        $adminDetail = AdminUser::find(Auth::guard('admin')->user()->id);
        $adminRole = $adminDetail->role->type;



        Excel::create($filename, function($excel) use ($offers,$adminRole){

        $excel->sheet('Offers', function($sheet) use (&$offers,&$adminRole){

                $sheet->loadView('admin.exportOffer')->withOffers($offers)->withAdminRole($adminRole);

            });

        })->download($type);
    }
    public function visited($tour_id = "")
    {
        if($tour_id != "")
        {
            $tour_data = $this->tour->find($tour_id);
            if($tour_data)
            {

                $tour_data->visited ='yes';
                $tour_data->visited_date =date('Y-m-d');

                $tour_data->save();
                Session::flash("success", "Tour Marked as Visited");
            }
            else{
                Session::flash("error", "ERROR! While Processing!");
            }
        }
        return redirect()->back();
    }
    public function VisitedDateUpdate(Request $request)
    {

            $tour_data = $this->tour->find($request->input('tour_id'));
            if($tour_data)
            {


                $tour_data->visited_date =$request->input('visited_date');

                $tour_data->save();
                Session::flash("success", "Visited date updated");
            }
            else{
                Session::flash("error", "ERROR! While Processing!");
            }

        return redirect()->back();
    }
    public function changeOfferStatus($offer_id, $status, $type = 'sale')
    {
        $offer_id = Crypt::decrypt($offer_id);
        $status = Crypt::decrypt($status);
        $offer = Offer::find($offer_id);
        if(!empty($offer)) {
            $offer->accept_status = $status;
            $offer->save();

            if($status == 1) {
                $str_status = 'accepted';
                //soft delete property
                $asset = property::find($offer->asset_id);
                $asset->deleted = 1;
                $asset->save();
            } elseif($status == 2) {
                 $str_status = 'rejected';
            }

            $MailContent ['customer_name'] = ucfirst($offer->user->name) . ' ' . ucfirst($offer->user->lastname);
            if($offer->step_2_negotiate_flag == 0) {
                $MailContent ['customer_price'] = ($offer->property->property_deal == 'SALE')?$offer->owner_offer_price:$offer->owner_offer_price.'/mo';
            } else {
                $MailContent ['customer_price'] = ($offer->property->property_deal == 'SALE')?$offer->customer_offer_price:$offer->customer_offer_price.'/mo';
            }
            $MailContent ['status'] = $status;
            $MailContent ['asset_type'] = $offer->property->property_deal;
            $MailContent ['asset_name'] = $offer->property->direccion;
            $MailContent ['asset_price'] = ($offer->property->property_deal == 'SALE')?$offer->property->price:$offer->property->price.'/mo';
            $MailContent ['url'] = url('property/detail')."/".$offer->asset_id;
            $MailContent['is_admin'] = '0';
            Loquare::sendMail('OfferStatusMail', $MailContent, 'Loquare - Your offer ' . $str_status, ucfirst($offer->user->name) . ' ' . ucfirst($offer->user->lastname), $offer->user->email, '');

            //Mail to admin
            $MailContent['customer_name'] = 'Admin';
            $MailContent['is_admin'] = '1';
            Loquare::sendMail('OfferStatusMail', $MailContent, 'Loquare - Offer ' . $str_status, 'Loquare Admin', 'tester@loquare.com', '');

            return redirect('admin/offers-completed/'.$type)->with('success', 'Offer '.$str_status.' successfullly');
        } else {
            return redirect('admin/offers-completed/'.$type)->with('error', 'Offer does not exist');
        }
    }

    public function deleteOffer($offer_id, $type = 'sale')
    {
        $offer_id = Crypt::decrypt($offer_id);
        $offer = Offer::find($offer_id);
        $chat = Chat::where('user_id', $offer->login_id)->where('asset_id', $offer->asset_id)->first();
        if(isset($chat->messages)) {
            $chat_messages = $chat->messages;
        }
        if(!empty($offer)) {
            $offer->delete();

            if(!empty($chat)) {
                $chat->delete();
            }

            if(isset($chat_messages) && !empty($chat_messages)) {
                $chat_messages->delete();
            }
            return redirect('admin/offers-completed/'.$type)->with('success', 'Offer removed successfullly');
        } else {
            return redirect('admin/offers-completed/'.$type)->with('error', 'Offer does not exist');
        }
    }

    public function getNewOffers() {
        return Loquare::getNewOffers();
    }

    public function unreadNotificationCount()
    {
        $notifications = Loquare::unreadNotifications();
        return response()->json(['count' => $notifications->count()]);
    }

    public function unreadNotifications()
    {
        $notifications = Loquare::unreadNotifications();
        return view('admin.notifications', compact('notifications'));
    }
}
