@extends('layouts.auth-layout')
@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Coffee Shop</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --coffee-dark: #3e2723;
            --coffee-main: #6f4e37;
            --coffee-soft: #a1887f;
            --coffee-cream: #f5ebe0;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: 
                linear-gradient(
                    rgba(255, 255, 255, 0.55),
                    rgba(62, 39, 35, 0.55)
                ),
                url('/storage/images/bg-coffee.png') center/cover no-repeat fixed;

            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* Glass Card */
        .login-card {
            width: 100%;
            max-width: 480px;
            background: var(--glass-bg);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,.35);
            animation: fadeUp 0.9s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .logo-wrapper img {
            width: 140px;
            height: auto;
            filter: drop-shadow(0 8px 20px rgba(0,0,0,.35));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        h2 {
            color: #743d1f;
            font-weight: 600;
            margin-bottom: 6px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        p {
            color: #684914;
            font-size: 14px;
            margin-bottom: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.35);
            background: rgba(255,255,255,.75);
            font-size: 14px;
            transition: all .25s ease;
            outline: none;
        }

        input:focus {
            border-color: var(--coffee-main);
            box-shadow: 0 0 0 3px rgba(111,78,55,.25);
            background: #fff;
        }

        /* Button */
        .btn-login {
            margin-top: 6px;
            padding: 14px;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, #6f4e37, #4e342e);
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all .3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,.35);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0,0,0,.45);
            background: linear-gradient(135deg, #8d6e63, #5d4037);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 26px 0;
            color: #eee;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.4);
        }

        /* Google Button */
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px;
            border-radius: 16px;
            background: rgba(255,255,255,.85);
            border: none;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: all .25s ease;
        }

        .google-btn img {
            width: 18px;
        }

        .google-btn:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,.25);
        }

        /* Register */
        .register {
            margin-top: 20px;
            font-size: 13px;
            color: #eee;
        }

        .register a {
            color: #ffe0b2;
            text-decoration: none;
            font-weight: 500;
        }

        .register a:hover {
            text-decoration: underline;
        }

        /* Error */
        .error-list {
            margin-top: 15px;
            padding: 12px;
            border-radius: 12px;
            background: rgba(255, 0, 0, 0.15);
            color: #ffebee;
            font-size: 13px;
            text-align: left;
        }

        .error-list li {
            margin-left: 18px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 22px;
            }
        }
    </style>
</head>

<body>

<div class="login-card">
 

    <h2>COFFEE SHOP</h2>
    <p>Masuk untuk menikmati pengalaman kopi terbaik</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="btn-login" type="submit">Login</button>
    </form>

    <div class="divider">atau</div>

    <a href="{{ route('google.login') }}" class="google-btn">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg">
        Login dengan Google
    </a>

    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="register">
        Belum punya akun? <a href="/register">Register</a>
    </div>
</div>

</body>
</html>
@endsection
