<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="{{ url('/register') }}">Register</a></p>

    @if(session('message'))
    <p style="color: red;">{{ session('message') }}</p>
    @endif
</body>

</html>