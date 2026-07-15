<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in — GoSoftware</title>
    @vite(['resources/css/admin.css'])
    <style>
        body { background: #0d1826; display: grid; place-items: center; min-height: 100vh; }
        .login-card { background: #fff; border-radius: 14px; padding: 34px 30px; width: min(380px, 92vw); }
        .login-card img { display: block; margin: 0 auto 24px; }
    </style>
</head>
<body>
<div class="login-card">
    <img src="{{ asset('images/logo-dark.png') }}" alt="GoSoftware" height="34">

    @if($errors->any())
        <div class="flash-err">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="field">
            <label class="check"><input type="checkbox" name="remember" value="1"> Remember me</label>
        </div>
        <button type="submit" class="btn" style="width:100%">Log in</button>
    </form>
</div>
</body>
</html>
