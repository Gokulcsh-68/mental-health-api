@component('mail::message')
# {{ $heading }}

Dear User,

Your Account Activation link is 
<a href="{{ config('app.web_url') }/auth/activate/{{$uid}}">
	Click to Activate Your Account
</a>

@endcomponent