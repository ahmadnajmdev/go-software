<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in — GoSoftware</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
    <style>
        body { background: var(--canvas); display: grid; place-items: center; min-height: 100vh; padding: 20px; }
        .login-card {
            background: #fff;
            border-radius: var(--r-panel);
            box-shadow: var(--shadow-panel);
            padding: 34px 30px;
            width: min(380px, 100%);
        }
        .login-card img { display: block; margin: 0 auto 8px; }
        .login-card .lede { text-align: center; font-size: 13px; color: var(--muted); margin: 0 0 24px; }
    </style>
</head>
<body>
<div class="login-card">
    <img src="{{ asset('images/logo-dark.png') }}" alt="GoSoftware" height="30">
    <p class="lede">Sign in to manage the site</p>

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
