@if($notifications->count() > 0)
<li class="header">You have {{$notifications->count()}} unread notifications</li>
<li>
    <ul class="menu">
    @forelse($notifications as $notification)
        <li>
            <a onclick="return readNotification({{$notification->id}});" href="{{Loquare::activityUrl($notification->activity_type)}}">
            {!! Loquare::activityIcon($notification->activity_type) !!}
            {{$notification->activity_text}}
            </a>
        </li>
    @empty
    @endforelse
    </ul>
</li>
@else
<li class="header">You have no unread notification</li>
@endif