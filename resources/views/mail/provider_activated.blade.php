@component('mail::message')
# {{ $heading }}

Dear User,

Your account has been activated successfully. 

<br>You may login now using URL: <a href="{{ $url }}">{{ $url }}</a>

Thanks
A2Z TeleHealth Team


@endcomponent