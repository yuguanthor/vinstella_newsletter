{!! $body !!}


@if( isset($unsubscribe_link) )
<hr>
If you do not want to receive this kind of email.<br>
You may <a href="{{$unsubscribe_link}}">Unsubscribe</a> it.
@endif