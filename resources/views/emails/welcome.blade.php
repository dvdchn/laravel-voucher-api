<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Application</title>
</head>
<body>
    <h1>Welcome, {{ $user->first_name }}!</h1>
    <p>Thank you for registering. Here is your voucher code: <strong>{{ $voucherCode }}</strong></p>
</body>
</html>
