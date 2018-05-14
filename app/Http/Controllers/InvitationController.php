<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\collection\CollectionModel as collect;
use App\Http\Controllers\CI_ModelController as common;
use Session;
use Redirect;
use App\collection\GrouplistModel as collectlist;
use App\properties\PropertyModel as property;
use DB;
use Illuminate\Support\Facades\Storage;
use Loquare;
use App\PropertyInvitation;
use App\User;
use Route;
use App\Http\Controllers\LogsController as activity;

class InvitationController extends Controller
{
	function __construct()
	{

	}

    function sendInvitation(Request $request)
    {
        $this->middleware('auth');
        $property_id = $request->property_id;
        $token = uniqid();

        $objPropertyInvitation = new PropertyInvitation();
        $invitationExist = PropertyInvitation::where("collection_id", $request->collection_id)->where("property_id", $property_id)->where("from_user", Auth::user()->id)->where("to_email", $request->email)->first();
        if(!empty($invitationExist)) {
            return redirect('collections/'.Auth::user()->name.'/'.$request->collection_id)->with('error', 'Invitation has already been sent');
        } else {
            $data = [
                "property_id" => $property_id,
                "collection_id" => $request->collection_id,
                "from_user" => Auth::user()->id,
                "to_email" => $request->email,
                "token" => $token
            ];
            $objPropertyInvitation->insertUpdate($data);

            $MailContent['user'] = $request->email;
            $MailContent['invitation_url'] = url('property/invitation')."/".$token;
            Loquare::sendMail('PropertyInvitation', $MailContent, 'Loquare - Property Invitation', $request->email, $request->email, '');

	        $property = property::find($property_id);
	        $log = array(
		        "type" => "invite-property",
		        "message" => "Send invitation to ".$request->email." for property ".$property->direccion
	        );
	        activity::addlog($log);

            return redirect('collections/'.Auth::user()->name.'/'.$request->collection_id)->with('success', 'Invitation sent successfully');
        }

    }

    function propertyInvitation($token)
    {
        $invitation = PropertyInvitation::where('token', $token)->first();
        if($invitation) {
            //Accept Invitation
            $user_exist = User::where('email', $invitation->to_email)->first();
            if($user_exist) {
                if(Auth::check()) {
                    Auth::logout();
                }
                Auth::login($user_exist);

                //Create user collection
                $table = new collect();
                $insert = array(
  					"user_id"       => Auth::user()->id,
  					"created_at"    => date('Y-m-d H:i:s')
  				);

                $collection = collect::find($invitation->collection_id);
                if($collection) {
                    $filter = array(
        				array("collection", "like", "%".$collection->collection."%"),
        				array("user_id", "=", Auth::user()->id),
        			);

        			$data = common::get_by_condition($table, $filter);
                    if($data == false) {
                        $insert['collection'] = $collection->collection;
                        $insert['total_property'] = 1;
                        $insert['reference_id'] = $invitation->collection_id;
          				$flag = common::insert_data($table, $insert);
                    } else {
                        $flag = $data[0]['id'];
                        $collection_t = collect::find($flag);
                        $collection_t->total_property = $collection_t->total_property + 1;
                        $collection_t->save();
                    }
                } else {
                    $insert['collection'] = "My Collection";
                    $insert['total_property'] = 1;
                    $insert['reference_id'] = $invitation->collection_id;
    				$flag = common::insert_data($table, $insert);
                }

                //Add property to collection
                $table = new collectlist();
                $filter = array(
    				array("property_id", "=", $invitation->property_id),
    				array("collection_id", "=", $flag),
    				array("user_id", "=", Auth::user()->id)
    			);
                $data = common::get_by_condition($table, $filter);

                if($data == false) {
                    $insert     = array(
        				"property_id"   => $invitation->property_id,
        				"collection_id" => $flag,
                        "property_from" => "invite",
        				"comment"       => "invite",
        				"user_id"       => Auth::user()->id,
        				"invited_by"    => $invitation->from_user,
                        'reference_id'  => $invitation->collection_id,
                        "created_at"    => date('Y-m-d h:i:s')
        			);
    			    $flag_property  = common::insert_data($table, $insert);
                }

                return redirect('collections/'.Auth::user()->name.'/'.$flag);
            } else {
                return redirect('login');
            }
        } else {
            //Invitation expired
            if(Auth::check()) {
                return redirect('collections/'.Auth::user()->name)->with('error', "Invitation doesn't exist");
            } else {
                return redirect('login')->with('error', "Invitation doesn't exist");
            }
        }
    }
}
