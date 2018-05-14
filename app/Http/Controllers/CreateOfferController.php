<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\OfferVisitSchedule;
use App\OfferBuyersInfo;
use Redirect;
use Auth;
use DB;
use File;
use Image;
use Illuminate\Support\Facades\Input;
use App\properties\PropertyModel as property;
use App\Http\Controllers\CI_ModelController as common;
use App\Http\Controllers\LogsController as activity;
use App\Chat;
use App\ChatMessage;
use App\AdminNotification;
use Crypt;
use Loquare;

class CreateOfferController extends Controller
{
	protected $data, $view, $searchdata, $styles, $scripts;

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['title'] = "Loquare";
	    $this->data['layout'] = "template";
	    $this->data['page'] = "Loquare";
        $this->objOffer = new Offer();
        $this->objChatMessage = new ChatMessage();
        $this->objChat = new Chat();
	    $this->styles = array();
	    $this->scripts = array();
    }

    public function load_view()
	{
		$this->data['scripts'] = $this->scripts;
		$this->data['styles'] = $this->styles;
		return view($this->view, $this->data);
	}

    public function index($property_id)
    {
        $this->data['property_id'] = $property_id;

        $filter = array("id" => $property_id);
        $table  = new property();
		$data   = common::get_single($table, $filter);

        $images = DB::table('loquare_uploads')->select('filename')->where("post_id", "=", $property_id);
    	$images = $images->where("post_type", "=", "property-image")->get();

    	$images = json_decode(json_encode($images), true);
    	$data['images'] = $images;

		$this->data['property'] = $data;
        $this->data['objProperty'] = property::find($property_id);

        $this->data['chat'] = Chat::where('user_id' , Auth::user()->id)->where('asset_id', $property_id)->first();

        $this->data['offer'] = Offer::where('login_id' , Auth::user()->id)->where('asset_id', $property_id)->first();

	    $log = array(
	    	"type" => "page",
		    "message" => "Visited buying offer page for property ".$data['direccion']
	    );
	    activity::addlog($log);

		$this->scripts = array(
			'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js',
			asset('/frontend/js/offers.js')
		);
        $this->view = 'buying-offer';
	    return $this->load_view();
    }

    public function saveOfferDpamount()
    {
        $requestdata = request()->all();
        $requestdata['login_id'] = Auth::user()->id;
        if(isset($requestdata['id']) && $requestdata['id'] > 0) {
            $this->objOffer->insertUpdate($requestdata);
            $offer_id = $requestdata['id'];
            return response()->json(['offer_id' => $offer_id], 200);
        } else {
            return response()->json([], 200);
        }
    }

    public function saveOffer()
    {
        $requestdata = request()->all();
        $requestdata['login_id'] = Auth::user()->id;

        if(isset($requestdata['id']) && $requestdata['id'] > 0) {
            $offer = Offer::find($requestdata['id']);
        } else {
            AdminNotification::create(['user_id' => 0, 'activity_type' => 'new_offer_started', 'activity_text' => 'New offer started', 'source_id' => 1, 'read_flag' => 0]);
            $asset = property::find($requestdata['asset_id']);
            if(isset($asset->agent_id) && $asset->agent_id > 0) {
                AdminNotification::create(['user_id' => 0, 'activity_type' => 'new_offer_started', 'activity_text' => 'New offer started', 'source_id' => $asset->agent_id, 'read_flag' => 0]);
            }
        }

        if(isset($requestdata['mortgage_certificate_1']) && isset($requestdata['payment_method_mortgage']) && $requestdata['payment_method_mortgage'] == 'Not Approved') {
            $filename = 'certificate_' .uniqid(). '.' . $requestdata['mortgage_certificate_1']->getClientOriginalExtension();
            $requestdata['mortgage_certificate_1']->move('offer-storage', $filename);
            $requestdata['payment_method_mortgage_certificate'] = $filename;

            if(isset($offer) && !empty($offer) && $offer['payment_method_mortgage_certificate'] != '' && file_exists(public_path('offer-storage/'.$offer['payment_method_mortgage_certificate']))) {
                unlink(public_path('offer-storage/'.$offer['payment_method_mortgage_certificate']));
            }
        }

        if(isset($requestdata['mortgage_certificate_2']) && isset($requestdata['payment_method_mortgage']) && $requestdata['payment_method_mortgage'] == 'Approved') {
            $filename = 'certificate_' .uniqid(). '.' . $requestdata['mortgage_certificate_2']->getClientOriginalExtension();
            $requestdata['mortgage_certificate_2']->move('offer-storage', $filename);
            $requestdata['payment_method_mortgage_certificate'] = $filename;

            if(isset($offer) && !empty($offer) && $offer['payment_method_mortgage_certificate'] != '' && file_exists(public_path('offer-storage/'.$offer['payment_method_mortgage_certificate']))) {
                unlink(public_path('offer-storage/'.$offer['payment_method_mortgage_certificate']));
            }
        }

        if(isset($requestdata['proof_of_income']) && isset($requestdata['payment_method']) && $requestdata['payment_method'] == 'Direct from Bank') {
            $filename = 'proof_of_income_' .uniqid(). '.' . $requestdata['proof_of_income']->getClientOriginalExtension();
            $requestdata['proof_of_income']->move('offer-storage', $filename);
            $requestdata['proof_of_income'] = $filename;

            if(isset($offer) && !empty($offer) && $offer['proof_of_income'] != '' && file_exists(public_path('offer-storage/'.$offer['proof_of_income']))) {
                unlink(public_path('offer-storage/'.$offer['proof_of_income']));
            }
        }

        if(isset($requestdata['payment_method_mortgage_dp_amount_not_approved']) && $requestdata['payment_method_mortgage_dp_amount_not_approved'] != '' && isset($requestdata['payment_method_mortgage']) && $requestdata['payment_method_mortgage'] == 'Not Approved') {
            $requestdata['payment_method_mortgage_dp_amount'] = $requestdata['payment_method_mortgage_dp_amount_not_approved'];
        } else if(isset($requestdata['payment_method_mortgage_dp_amount_approved']) && $requestdata['payment_method_mortgage_dp_amount_approved'] != ''  && isset($requestdata['payment_method_mortgage']) && $requestdata['payment_method_mortgage'] == 'Approved') {
            $requestdata['payment_method_mortgage_dp_amount'] = $requestdata['payment_method_mortgage_dp_amount_approved'];
        }

        if(isset($requestdata['photo_1'])) {
            $filename = 'buyer_photo_' .uniqid(). '.' . $requestdata['photo_1']->getClientOriginalExtension();
            $requestdata['photo_1']->move('offer-storage', $filename);
            $requestdata['photo_1'] = $filename;

            if(isset($offer) && !empty($offer) && $offer['photo_1'] != '' && file_exists(public_path('offer-storage/'.$offer['photo_1']))) {
                unlink(public_path('offer-storage/'.$offer['photo_1']));
            }
        }

        if(isset($requestdata['photo_2'])) {
            $filename = 'buyer_photo_' .uniqid(). '.' . $requestdata['photo_2']->getClientOriginalExtension();
            $requestdata['photo_2']->move('offer-storage', $filename);
            $requestdata['photo_2'] = $filename;

            if(isset($offer) && !empty($offer) && $offer['photo_2'] != '' && file_exists(public_path('offer-storage/'.$offer['photo_2']))) {
                unlink(public_path('offer-storage/'.$offer['photo_2']));
            }
        }

        if(isset($requestdata['step_2_negotiate_flag']) && $requestdata['step_2_negotiate_flag'] == 0)
        {
            $requestdata['customer_offer_price'] = null;
        }

        $offer = Offer::where('login_id' , Auth::user()->id)->where('asset_id', $requestdata['asset_id'])->first();
        if(!empty($offer)) {
            $requestdata['id'] = $offer['id'];
        }
        $response = $this->objOffer->insertUpdate($requestdata);

        if(isset($response->id)) {
            $offer_id = $response->id;
        } else {
            $offer_id = $requestdata['id'];
        }

        if(isset($requestdata['step_7_completed']) && $requestdata['step_7_completed'] == 1) {
            AdminNotification::create(['user_id' => 0, 'activity_type' => 'new_offer_received', 'activity_text' => 'New offer received', 'source_id' => 1, 'read_flag' => 0]);
            $asset = property::find($requestdata['asset_id']);
            if(isset($asset->agent_id) && $asset->agent_id > 0) {
                AdminNotification::create(['user_id' => 0, 'activity_type' => 'new_offer_received', 'activity_text' => 'New offer received', 'source_id' => $asset->agent_id, 'read_flag' => 0]);
            }
            $MailContent = [];
            $offer = Offer::find($requestdata['id']);

            $str_payment_method = '';
            if($offer->step_1_completed == 1){
                $str_payment_method .= $offer->payment_method;
                if($offer->payment_method == 'Mortgage') {
                    $str_payment_method .= ", ". $offer->payment_method_mortgage;
                    $str_payment_method .= ", DP - &euro;". $offer->payment_method_mortgage_dp_amount;
                }
            }

            $str_offered_price = '';
            if($offer->step_2_completed == 1){
                if(isset($offer['customer_offer_price']) && $offer['customer_offer_price'] != '') {
                    $str_offered_price = $offer['customer_offer_price'];
                } else {
                    $str_offered_price = $offer['owner_offer_price'];
                }
            }

            $str_your_detail = '';
            if($offer->step_3_completed == 1){
                $str_your_detail .= $offer['customer_name'] . ", " . $offer['customer_phone'] . ", " . $offer['customer_email'];
            }

            $str_visit_schedule = '';
            if($offer->step_4_completed == 1){
                $str_visit_schedule .= ($offer->schedule_visit_1 != '')?$offer->schedule_visit_1:'';
                $str_visit_schedule .= ($offer->schedule_visit_2 != '')? ', '.$offer->schedule_visit_2:'';
            }

            $str_buyer_info = '';
            if($offer->step_6_completed == 1){
                if($offer->first_name_1 != '' || $offer->last_name_1 != '') {
                    if($offer->first_name_1 != '') {
                        $str_buyer_info .= $offer->first_name_1;
                    }

                    if($offer->last_name_1 != '') {
                        $str_buyer_info .= " " . $offer->last_name_1;
                    }
                }

                if($offer->first_name_2 != '' || $offer->last_name_2 != '') {
                    if($offer->first_name_2 != '') {
                        $str_buyer_info .= ", " . $offer->first_name_2;
                    }

                    if($offer->last_name_2 != '') {
                        $str_buyer_info .= " " . $offer->last_name_2;
                    }
                }
            }

            $str_signature_schedule = '';
            if($offer->step_7_completed == 1){
                $str_signature_schedule .= ($offer->signature_schedule_1 != '')?$offer->signature_schedule_1:'';
                $str_signature_schedule .= ($offer->signature_schedule_2 != '')? ', '.$offer->signature_schedule_2:'';
                $str_signature_schedule .= ($offer->signature_schedule_3 != '')? ', '.$offer->signature_schedule_3:'';
            }

            $MailContent['name'] = Auth::user()->name . " " . Auth::user()->lastname;
            $MailContent['asset'] = $offer->property->direccion;
            $MailContent['payment_method'] = $str_payment_method;
            $MailContent['offered_price'] = "&euro;". $str_offered_price;
            $MailContent['your_detail'] = $str_your_detail;
            $MailContent['visit_schedule'] = $str_visit_schedule;
            $MailContent['rental_period'] = '';
            $MailContent['type'] = $offer->property->property_deal;

            if($offer->property->property_deal == 'RENT') {
                if($offer->rental_period == 1) {
                    $MailContent['rental_period'] = "1 Year";
                } elseif($offer->rental_period == 2) {
                    $MailContent['rental_period'] = "3 Year";
                } elseif($offer->rental_period == 3) {
                    $MailContent['rental_period'] = "> 3 Year";
                }
            }
            $MailContent['buyer_info'] = $str_buyer_info;
            $MailContent['signature_schedule'] = $str_signature_schedule;

            //Mail to logged in user
            Loquare::sendMail('UserOfferConfirmation', $MailContent, 'Loquare - Offer Confirmation', Auth::user()->name . " " . Auth::user()->lastname, Auth::user()->email, '');

            //Mail to contact email provided during creating offer
            $MailContent['name'] = $offer->customer_name;
            Loquare::sendMail('UserOfferConfirmation', $MailContent, 'Loquare - Offer Confirmation', $offer->customer_name, $offer->customer_email, '');

            //Mail to system admin
            $MailContent['name'] = 'Admin';
            Loquare::sendMail('UserOfferConfirmation', $MailContent, 'Loquare - Offer Confirmation', 'Admin', 'tester@loquare.com', '');

            $property_id = $requestdata['asset_id'];
            $this->data['property_id'] = $property_id;

            $filter = array("id" => $property_id);
            $table  = new property();
    		$data   = common::get_single($table, $filter);

            $images = DB::table('loquare_uploads')->select('filename')->where("post_id", "=", $property_id);
        	$images = $images->where("post_type", "=", "property-image")->get();

        	$images = json_decode(json_encode($images), true);
        	$data['images'] = $images;

            $this->data['property'] = $data;
            $this->data['offer_completed'] = 1;
            $this->view = 'buying-offer';
    	    return $this->load_view();
        }

        return response()->json(['offer_id' => $offer_id], 200);
    }

    public function downloadContractSelling()
    {
        $file= public_path(). "/offer-storage/contract/selling.pdf";

        $headers = array(
                  'Content-Type: application/pdf',
                );

        return response()->download($file, 'selling_contract.pdf', $headers);
    }

    public function downloadContractRental()
    {
        $file= public_path(). "/offer-storage/contract/rental.pdf";

        $headers = array(
                  'Content-Type: application/pdf',
                );

        return response()->download($file, 'rental_contract.pdf', $headers);
    }

    public function saveMessage(Request $request)
    {
        $requestData = $request->all();
        if(isset($requestData['chat_id'])){
            $chat_id = $requestData['chat_id'];
        }
        if(isset($requestData['chat_id']) && $requestData['chat_id'] == 0) {
            unset($requestData['chat_id']);
        }
        $requestData['user_id'] = Auth::user()->id;
        if(isset($chat_id) && $chat_id == 0) {
            $response = $this->objChat->insertUpdate($requestData);
            $chat_id = $response->id;
            $requestData['chat_id'] = $chat_id;
            $this->objChatMessage->insertUpdate($requestData);
            $chat = Chat::find($chat_id);
        } else {
            $this->objChatMessage->insertUpdate($requestData);
            $chat = Chat::find($chat_id);
        }

        $this->data['chat'] = $chat;
        $this->data['all'] = 1;
        $this->data['chat_id'] = $chat_id;
        $this->view = 'offer-chat-ajax';
	    return $this->load_view();
    }

    public function updateChat(Request $request)
    {
        $requestData = $request->all();
        $chat = Chat::find($requestData['chat_id']);
        if($chat) {
            $new_message_count = $chat->messages->where('chat_id', $requestData['chat_id'])->where('read_flag', 0)->where('user_id', 0)->count();
            if($new_message_count > 0) {
                ChatMessage::where('chat_id', $requestData['chat_id'])->where('read_flag', 0)->where('user_id', 0)->update(['read_flag' => 1]);
            }
            $chat_id = $requestData['chat_id'];
            return view('offer-chat-ajax', compact('chat','new_message_count', 'chat_id'));
        } else {
            return '';
        }
    }

    public function cancelOffer($id)
    {
        $id = Crypt::decrypt($id);
        $offer = Offer::find($id);
        if(!empty($offer)) {
            $chat = Chat::where('user_id', $offer->login_id)->where('asset_id', $offer->asset_id)->first();
            if(isset($chat->messages)) {
                $chat_messages = $chat->messages;
            }

            $offer->delete();

            if(!empty($chat)) {
                $chat->delete();
            }

            if(isset($chat->messages)) {
                $chat->messages()->delete();
            }

            return redirect('my/offers')->with('success', 'Offer removed successfullly');
        } else {
            return redirect('my/offers')->with('error', 'Offer does not exist');
        }
    }

    public function sendQuery(Request $request)
    {
        $requestData = $request->all();

        $MailContent ['customer_name'] = ucfirst(Auth::user()->name) . " " . ucfirst(Auth::user()->lastname);
        $MailContent ['customer_email'] = Auth::user()->email;
        $MailContent ['customer_query'] = $requestData['query'];
        Loquare::sendMail('OfferLetUsKnow', $MailContent, 'Loquare - Customer Query', 'Loquare Admin', 'tester@loquare.com', '');

        return redirect('create-offer/'.$requestData['asset_id'])->with('success', 'Your query has been sent successfully');
    }

    public function modifyOffer($id)
    {
        $offer = Offer::find($id);
        if(!empty($offer)) {
            $offer->step_7_completed = 0;
            $offer->accept_status = 0;
            $offer->save();
            return redirect('create-offer/'.$offer->asset_id);
        } else {
            return redirect('');
        }
    }
}