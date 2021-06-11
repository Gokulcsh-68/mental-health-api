@component('mail::message')
# {{ $heading }}

Dear User,

Your Account Activation link is 
<a href="{{ $url }}">
	Click to Activate Your Account
</a>

@endcomponent