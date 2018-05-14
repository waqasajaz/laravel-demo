<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Input;
use Redirect;
use App\Chat;
use App\ChatMessage;
use App\AdminUser;
use App\properties\PropertyModel as property;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->objChatMessage = new ChatMessage();
        $this->objChat = new Chat();
    }

    public function index($type = 'sale')
    {
        $filters = [];
        if(Auth::guard('admin')->user()->role->type == 'agent'){
            $agentAssets = property::where('agent_id', Auth::guard('admin')->user()->id)->pluck('id', 'direccion');
            if(count($agentAssets) > 0) {
                $filters['asset_id'] = $agentAssets->toArray();
            } else {
                $filters['asset_id'] = [0];
            }
        }

        $filters['property_deal'] = $type;

        $chats = $this->objChat->getAll($filters);
        if(Auth::guard('admin')->user()->role->type == 'agent'){
            return view('admin.chats', compact('chats', 'agentAssets', 'type'));
        } else {
            return view('admin.chats', compact('chats', 'type'));
        }
    }

    public function updateChat(Request $request)
    {
        $requestData = $request->all();
        $chat = Chat::find($requestData['chat_id']);

        if(Auth::guard('admin')->user()->role->type == 'agent') {
            $new_message_count = $chat->messages->where('chat_id', $requestData['chat_id'])->where('read_flag_agent', 0)->count();
        }

        if(Auth::guard('admin')->user()->role->type == 'admin') {
            $new_message_count = $chat->messages->where('chat_id', $requestData['chat_id'])->where('read_flag_admin', 0)->count();
        }

        if($new_message_count > 0) {
            if(Auth::guard('admin')->user()->role->type == 'agent') {
                ChatMessage::where('chat_id', $requestData['chat_id'])->where('read_flag_agent', 0)->update(['read_flag_agent' => 1]);
            }

            if(Auth::guard('admin')->user()->role->type == 'admin') {
                ChatMessage::where('chat_id', $requestData['chat_id'])->where('read_flag_admin', 0)->update(['read_flag_admin' => 1]);
            }
        }

        return view('admin.chatAjax', compact('chat','new_message_count'));
    }

    public function saveMessage(Request $request)
    {
        $requestData = $request->all();
        $requestData['agent_id'] = Auth::guard('admin')->user()->id;
        $this->objChatMessage->insertUpdate($requestData);
        $chat = Chat::find($requestData['chat_id']);
        $all = 1;

        if(Auth::guard('admin')->user()->role->type == 'agent') {
            $new_message_count = $chat->messages->where('chat_id', $requestData['chat_id'])->where('read_flag_agent', 0)->count();
        }

        if(Auth::guard('admin')->user()->role->type == 'admin') {
            $new_message_count = $chat->messages->where('chat_id', $requestData['chat_id'])->where('read_flag_admin', 0)->count();
        }

        if($new_message_count > 0) {
            if(Auth::guard('admin')->user()->role->type == 'agent') {
                ChatMessage::where('chat_id', $requestData['chat_id'])->where('read_flag_agent', 0)->update(['read_flag_agent' => 1]);
            }

            if(Auth::guard('admin')->user()->role->type == 'admin') {
                ChatMessage::where('chat_id', $requestData['chat_id'])->where('read_flag_admin', 0)->update(['read_flag_admin' => 1]);
            }
        }

        return view('admin.chatAjax', compact('chat', 'all','new_message_count'));
    }

    public function updateChatWindows(Request $request)
    {
        $filters = [];
        $postData = $request->all();
        if(isset($postData['start_date']) && $postData['end_date']) {
            $range = [$postData['start_date'], $postData['end_date']];
        }

        if(Auth::guard('admin')->user()->role->type == 'agent') {
            $agentAssets = property::where('agent_id', Auth::guard('admin')->user()->id)->pluck('id');

            if(isset($postData['asset_id']) && $postData['asset_id'] != '') {
                $filters['asset_id'] = [$postData['asset_id']];
            } else if(count($agentAssets) > 0) {
                $filters['asset_id'] = $agentAssets->toArray();
            } else {
                $filters['asset_id'] = [0];
            }

            if(isset($range) && !empty($range)){
                $filters['date_range'] = $range;
            }

            if(!isset($postData['all'])){
                $filters['read_flag_agent'] = 0;
            }

            if(isset($filters['property_deal']) && in_array($filters['property_deal'], ['sale', 'rent'])) {
                $filters['property_deal'] = $postData['property_deal'];
            }

            $newChats = $this->objChat->getAll($filters);
            Chat::where('read_flag_agent', 0)->update(['read_flag_agent' => 1]);
        }

        if(Auth::guard('admin')->user()->role->type == 'admin') {
            if(!isset($postData['all'])){
                $filters['read_flag_admin'] = 0;
            }

            if(isset($range) && !empty($range)){
                $filters['date_range'] = $range;
            }

            if(isset($postData['property_deal']) && in_array($postData['property_deal'], ['sale', 'rent'])) {
                $filters['property_deal'] = $postData['property_deal'];
            }

            $newChats = $this->objChat->getAll($filters);
            Chat::where('read_flag_admin', 0)->update(['read_flag_admin' => 1]);
        }

        return view('admin.chatWindowAjax', compact('newChats'));
    }
}
