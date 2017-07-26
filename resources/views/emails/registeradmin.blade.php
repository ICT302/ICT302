<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h2>Hi {{ucwords($admin->first_name)}} {{ucwords($admin->last_name)}},</h2>

<p>You are registered as a new admin.</p>
<p>Your username is {{$admin->username}}.</p>
<p>Your password is {{$admin->plain_password}}</p>
<p>You may login now.</p>

</body>
</html>