<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
</head>

<body>
    <h2>Welcome to Your Dashboard</h2>
    <p>You are logged in!</p>
    <a href="{{ url('/logout') }}">Logout</a>

    @if(session('message'))
    <p style="color: green;">{{ session('message') }}</p>
    @endif
</body>

</html>