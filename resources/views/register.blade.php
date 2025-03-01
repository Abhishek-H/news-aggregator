<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ url('/login') }}">Login</a></p>

    @if(session('message'))
    <p style="color: green;">{{ session('message') }}</p>
    @endif
</body>

</html>