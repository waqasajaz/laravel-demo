@if((isset($new_message_count) && $new_message_count > 0) || (isset($all) && $all))
    @if(isset($new_message_count) && $new_message_count > 0)
    <script>
        $('#notification_{{$chat->id}}').html({{$new_message_count}});
    </script>
    @endif
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
@endif