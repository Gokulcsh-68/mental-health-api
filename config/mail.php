<?php
return [
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| Mail Driver
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| Laravel supports both SMTP and PHP's “mail” function as drivers for the
| sending of e-mail. You may specify which one you're using throughout
| your application here. By default, Laravel is setup for SMTP mail.
|
| Supported: “smtp”, “sendmail”, “mailgun”, “mandrill”, “ses”,
| “sparkpost”, “log”, “array”
|
*/
'driver' => env('MAIL_DRIVER', 'smtp'),
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| SMTP Host Address
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| Here you may provide the host address of the SMTP server used by your
| applications. A default option is provided that is compatible with
| the Mailgun mail service which will provide reliable deliveries.
|
*/
// 'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
'host' => env('MAIL_HOST', 'mailtrap.io'),
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| SMTP Host Port
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| This is the SMTP port used by your application to deliver e-mails to
| users of the application. Like the host we have set this value to
| stay compatible with the Mailgun e-mail application by default.
|
*/
// 'port' => env('MAIL_PORT', 587),
'port' => env('MAIL_PORT', 2525),
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| Global “From” Address
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| You may wish for all e-mails sent by your application to be sent from
| the same address. Here, you may specify a name and address that is
| used globally for all e-mails that are sent by your application.
|
*/
'from' => [
	'address' => env('MAIL_FROM_ADDRESS', 'admin@repay.ng'),
	'name' => env('MAIL_FROM_NAME', 'Repay Investment and Loans'),
],
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| E-Mail Encryption Protocol
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| Here you may specify the encryption protocol that should be used when
| the application send e-mail messages. A sensible default using the
| transport layer security protocol should provide great security.
|
*/
'encryption' => env('MAIL_ENCRYPTION', 'tls'),
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| SMTP Server Username
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| If your SMTP server requires a username for authentication, you should
| set it here. This will get used to authenticate with your server on
| connection. You may also set the “password” value below this one.
|
*/
'username' => env('MAIL_USERNAME'),
'password' => env('MAIL_PASSWORD'),
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| Sendmail System Path
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| When using the “sendmail” driver to send e-mails, we will need to know
| the path to where Sendmail lives on this server. A default path has
| been provided here, which will work well on most of your systems.
|
*/
'sendmail' => '/usr/sbin/sendmail -bs',
/*
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
| Markdown Mail Settings
| — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —
|
| If you are using Markdown based email rendering, you may configure your
| theme and component paths here, allowing you to customize the design
| of the emails. Or, you may simply stick with the Laravel defaults!
|
*/
'markdown' => [
	'theme' => 'default',
	'paths' => [
		resource_path('views/mail'),
	],
],
];