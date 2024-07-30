<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<form method="POST" action="{{ route('otp.send') }}">
    @csrf
    <label for="phone">Phone:</label>
    <input type="phone" name="phone" required>
    <button type="submit">Send OTP</button>
</form>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</body>
</html>
