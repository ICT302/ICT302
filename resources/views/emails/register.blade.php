<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h2>Hi {{ucwords($user->first_name)}} {{ucwords($user->last_name)}},</h2>

<p>Your username is {{$user->username}}.</p>
<p>Thanks for registering with us. You may login now.</p>

</body>
</html>