<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Route;
use Loquare;
use Session;
use Redirect;
use App\PropertyChat;
use App\PropertyChatMessage;
use App\collection\GrouplistModel as CollectionProperty;
use App\collection\CollectionModel as Collection;

class PropertyChatController extends Controller
{
	function __construct()
	{
        $this->middleware('auth');
	}

    public function propertyChat(Request $request)
    {
        $requestData = $request->all();
        $chat = PropertyChat::where('property_id', $requestData['property_id'])->first();
        $set_chat_id = 1;
        if(empty($chat)) {
            $chat = PropertyChat::create(['property_id' => $requestData['property_id']]);
        }

        return view('property-chat-ajax', compact('chat', 'set_chat_id'));
    }

    public function saveMessage(Request $request)
    {
        $requestData = $request->all();
        $chat = PropertyChat::find($requestData['chat_id']);
        $chat->messages()->create(['chat_id' => $requestData['chat_id'], 'user_id' => Auth::user()->id, 'message' => $requestData['message']]);

        return view('property-chat-ajax', compact('chat'));
    }

    public function updateChat(Request $request)
    {
        $requestData = $request->all();
        $user_id = Auth::user()->id;
        $unread_messages_count = PropertyChatMessage::where('chat_id', $requestData['chat_id'])->whereRaw("NOT FIND_IN_SET($user_id,read_by)")->count();
        if($unread_messages_count > 0) {
            $chat = PropertyChat::find($requestData['chat_id']);
            return view('property-chat-ajax', compact('chat'));
        } else {
            return '';
        }
    }

    public function removeUser(Request $request)
    {
        $requestData = $request->all();
        if(isset($requestData['invited_users']) && !empty($requestData['invited_users'])) {
            foreach($requestData['invited_users'] as $user) {
                $CollectionProperty = CollectionProperty::find($user);
                $collection = Collection::find($CollectionProperty->collection_id);
                $CollectionProperty->delete();
                $collection->total_property = $collection->total_property - 1;
                $collection->save();
            }
        }

        return redirect('collections/'.Auth::user()->name.'/'.$request->collection_id)->with('success', 'User removed successfuly');
    }
}
