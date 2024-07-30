<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>
<h2>Verify OTP</h2>
<form method="POST" action="{{ route('otp.check') }}">
    @csrf
    <input type="hidden" name="email" value="{{ session('email') }}">
    <label for="otp">OTP:</label>
    <input type="text" name="otp" required>
    <button type="submit">Verify</button>
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
