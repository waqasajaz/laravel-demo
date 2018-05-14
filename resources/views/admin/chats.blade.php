@extends('admin.master')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group pull-right">
                <div class="input-group">
                    <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                            <i class="fa fa-calendar"></i> Date range picker
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
            </div>
            @if(Auth::guard('admin')->user()->role->type == 'agent')
            <div class="form-group pull-right">
                <div class="input-group">
                    <select class="form-control btn btn-default" onchange="return filter_chats_asset(this.value);">
                        <option value="">All</option>
                        @forelse($agentAssets as $name => $id)
                            <option value="{{$id}}">{{$name}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="row" id="chat_windows">
        <script>
            var chatIds = new Array();
        </script>
        @forelse($chats as $chat)
        
            <script>
                chatIds.push({{$chat->id}});
            </script>
            <div class="col-md-4">
                <!-- DIRECT CHAT -->
                <div class="box direct-chat direct-chat-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="padding-right: 44px;">{{str_limit(ucfirst($chat->user->name).' '.ucfirst($chat->user->lastname).' - '.ucfirst($chat->property->direccion), 44, '...')}}</h3>
                        <div class="box-tools pull-right">
                            <!-- <span data-toggle="tooltip" title="Unread Messages" class="badge bg-red" id="notification_{{$chat->id}}">{{($chat->messages->where('read_flag', 0)->where('agent_id', 0)->count())?$chat->messages->where('read_flag', 0)->where('agent_id', 0)->count():''}}</span> -->
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
    </div>
</section>
@stop
@section('script')
<script>
    $(function() {
      var wtf    = $('.direct-chat-messages');
      var height = wtf[0].scrollHeight;
      wtf.scrollTop(height);
    });
    var start_date = '';
    var end_date = '';
    var asset_id = '';
    function send_message(form, chat_id)
    {
        
        var data = new FormData(form);
        var messageData = $('#message_input_'+chat_id).val();

        if(messageData != '')
        {
            $('#gif').show();
            var token = '<?php echo csrf_token() ?>';
            $.ajax({
                headers: { 'X-CSRF-TOKEN': token },
                type: "POST",
                url: "{{url('admin/chat/message')}}",
                data: data,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $('#gif').hide();
                    $('#messages_box_'+chat_id).html(response);
                    var wtf    = $('#messages_box_'+chat_id);
                    var height = wtf[0].scrollHeight;
                    wtf.scrollTop(height);
                    $('#message_input_'+chat_id).val('')
                }
            });    
        }
    }

    setInterval(updateChatWindow, 5000);

    function updateChatWindow() {
        for (i = 0; i < chatIds.length; i++) {
            var chat_id = chatIds[i];
            $.ajax({
                url: "{{ url('/admin/chat/update') }}",
                type: 'post',
                async: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id" : chat_id
                },
                success: function(response) {
                    if(response != '') {
                        $('#messages_box_'+chat_id).html(response);
                        var wtf    = $('#messages_box_'+chat_id);
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                    }
                }
            });
        }
    }

    setInterval(updateChats, 5000);

    function updateChats() {
        $.ajax({
            url: "{{ url('/admin/chats/update') }}",
            type: 'post',
            async: false,
            data: {
                "_token": '{{ csrf_token() }}',
                "start_date": start_date,
                "end_date": end_date,
                "asset_id": asset_id,
                "property_deal": '<?php echo $type; ?>'
            },
            success: function(response) {
                if(response != '') {
                    $('#chat_windows').prepend(response);
                }
            }
        });
    }

    function filter_chats_asset(asset) {
        asset_id = asset;

        $.ajax({
            url: "{{ url('/admin/chats/update') }}",
            type: 'post',
            async: false,
            data: {
                "_token": '{{ csrf_token() }}',
                "start_date": start_date,
                "end_date": end_date,
                "asset_id": asset_id,
                "property_deal": '<?php echo $type; ?>',
                "all": 1
            },
            success: function(response) {
                $('#chat_windows').html(response);
            }
        });
    }

    function enter_message(form,chat_id,e)
    {
        if (e.which == 13) {
            send_message(form,chat_id);
            return false;
        }        
    }

    $('#daterange-btn').daterangepicker(
        {
            ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            start_date = start.format('YYYY-MM-DD') + ' 00:00:00';
            end_date = end.format('YYYY-MM-DD') + ' 23:59:59';
            console.log(start_date + ' - ' + end_date);
            $('#gif').show();
            $.ajax({
                url: "{{ url('/admin/chats/update') }}",
                type: 'post',
                async: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    "start_date": start_date,
                    "end_date": end_date,
                    "asset_id": asset_id,
                    "property_deal": '<?php echo $type; ?>',
                    "all": 1
                },
                success: function(response) {
                    $('#chat_windows').html(response);
                    $('#gif').hide();
                }
            });
        }
    )
</script>
@stop