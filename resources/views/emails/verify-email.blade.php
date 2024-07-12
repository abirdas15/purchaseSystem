<html>
<body>

<p>Hello {{ $user->username }},</p>

<p>Please click the button below to verify your email address.</p>

<a href="{{ route('auth.email.verify.success', ['id' => $user->id]) }}">Verify Email Address</a>

<p>
    Thanks,<br>
    {{ config('app.name') }}
</p>
</body>
</html>
