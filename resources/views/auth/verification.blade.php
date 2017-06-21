Hi, {{ $usr['name'] }}

Please active your account : {{ url('user/activation', $usr['email_verification_code'])}}