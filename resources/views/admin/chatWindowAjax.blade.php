@forelse($newChats as $chat)
    <script>
        chatIds.push({{$chat->id}});
    </script>
    <div class="col-md-4">
        <!-- DIRECT CHAT -->
        <div class="box direct-chat direct-chat-warning">
            <div class="box-header with-border">
                <h3 class="box-title" style="padding-right: 44px;">{{str_limit(ucfirst($chat->user->name).' '.ucfirst($chat->user->lastname).' - '.ucfirst($chat->property->direccion), 44, '...')}}</h3>
                <div class="box-tools pull-right">
                    <!-- <span data-toggle="tooltip" title="Unread Messages" class="badge bg-red">{{($chat->messages->where('read_flag', 0)->where('agent_id', 0)->count())?$chat->messages->where('read_flag', 0)->where('agent_id', 0)->count():''}}</span> -->
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages" id="messages_box_{{$chat->id}}">
                    @if(isset($chat->messages))
                        @forelse($chat->messages as $message)
                            @if($message->user_id)
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">{{ucfirst($message->user->name)}} {{ucfirst($message->user->lastname)}}</span>
                                        <span class="direct-chat-timestamp pull-right">{{date('d M H:i a', strtotime($message->created_at))}}</span>
                                    </div>
                                    <!-- /.direct-chat-info -->
                                    <img class="direct-chat-img" src="{{asset('offer/images/agent-ramirez.png')}}" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        {{$message->message}}
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                            @elseif($message->agent_id == Auth::guard('admin')->user()->id)
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-right">You</span>
                                        <span class="direct-chat-timestamp pull-left">{{date('d M H:i a', strtotime($message->created_at))}}</span>
                                    </div>
                                    <!-- /.direct-chat-info -->
                                    <img class="direct-chat-img" src="{{asset('backend/images/avatar5.png')}}" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        {{$message->message}}
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                            @else
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">
                                            {{ucfirst($message->agent->name)}}
                                        </span>
                                        <span class="direct-chat-timestamp pull-right">{{date('d M H:i a', strtotime($message->created_at))}}</span>
                                    </div>
                                    <!-- /.direct-chat-info -->
                                    <img class="direct-chat-img" src="{{asset('backend/images/avatar5.png')}}" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        {{$message->message}}
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                            @endif
                        @empty
                        @endforelse
                    @endif
                </div>
                <!--/.direct-chat-messages-->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <form method="post">
                    <div class="input-group">
                        <input type="text" name="message" id="message_input_{{$chat->id}}" placeholder="Type Message ..." class="form-control" onkeypress="return enter_message(this.form,{{$chat->id}},event);">
                        <input type="hidden" name="chat_id" id="chat_id_{{$chat->id}}" value="{{$chat->id}}"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat" onclick="return send_message(this.form,{{$chat->id}});">Send</button>
                          </span>
                    </div>
                </form>
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
@empty
@endforelse