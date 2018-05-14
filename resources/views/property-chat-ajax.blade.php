@if(isset($set_chat_id) && $set_chat_id == 1)
    <script>
        $('#chat_id').val({{$chat->id}});
    </script>
@endif
@if(isset($chat->messages))
    @forelse($chat->messages as $message)
        <?php
            $old_read_by = explode(",", $message->read_by);
            array_push($old_read_by, Auth::user()->id);
            $new_read_by = array_unique($old_read_by);
            $new_read_by = array_filter($new_read_by);
            $new_read_by = implode(",", $new_read_by);
            $message->read_by = $new_read_by;
            $message->save();
        ?>
        @if($message->user_id == Auth::user()->id)
            <div class="direct_chat_view message_right clearfix">
                <div class="message_right_date">{{date('d M h:i a', strtotime($message->created_at))}}</div>
                <div class="chat_profile">{{(isset($message->user->name))?substr($message->user->name, 0, 1):''}}{{(isset($message->user->name))?substr($message->user->lastname, 0, 1):''}}</div>
                <div class="direct_chat_message">{{$message->message}}</div>
            </div>
        @else
            <div class="direct_chat_view message_left clearfix">
                <div class="message_left_date">{{date('d M h:i a', strtotime($message->created_at))}}</div>
                <div class="chat_profile">{{(isset($message->user->name))?substr($message->user->name, 0, 1):''}}{{(isset($message->user->name))?substr($message->user->lastname, 0, 1):''}}</div>
                <div class="direct_chat_message">{{$message->message}}</div>
            </div>
        @endif
    @empty
    @endforelse
@endif