<h1>Password Reset</h1>

<p>Hi {{$user['username']}},</p>

<p>SOmeone, hopefully you, requested to reset your password on the TrueBlue Mapminer system.  You can use this link to create a new password:</p>
<a href='{{ URL::to('user/reset/'.$token) }}'>
    {{ URL::to('user/reset/'.$token)  }}
</a>

<p>Sincerely</p>