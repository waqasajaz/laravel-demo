@if((isset($new_message_count) && $new_message_count > 0) || (isset($all) && $all))
    <script>
        $('#chat_id').val({{$chat_id}});
    </script>
    @if(isset($new_message_count) && $new_message_count > 0)
    <script>
        $('#notification_{{$chat->id}}').html({{$new_message_count}});
    </script>
  @endif
  @if(isset($chat->messages))
    @forelse($chat->messages as $message)
      @if($message->user_id)
            <div class="right-chat-view clearfix">
              <div class="pull-right chat-datetime-right">{{date('d M h:i a', strtotime($message->created_at))}}</div>
              <div class="txt-chat">
                 <p>{{$message->message}}</p>
              </div>
              <div class="user-img img-round">
                  <img style="height: 42px;width: 42px;" src="{{asset('offer/images/agent-ramirez.png')}}" alt="" title="You">
              </div>
          </div>
        @else
            <div class="left-chat-view clearfix">
              <div class="chat-datetime-left">{{date('d M h:i a', strtotime($message->created_at))}}</div>
              <div class="user-img img-round">
                  <img style="height: 42px;width: 42px;" src="{{asset('backend/images/avatar5.png')}}" alt="" title="{{ucfirst($message->agent->name)}}">
              </div>
              <div class="txt-chat">
                  <p>{{$message->message}}</p>
              </div>
          </div>
      @endif
      @empty
    @endforelse
  @endif
@endif