@extends('layouts.auth-layout')
@section('content')
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register coffee shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('/storage/images/bg-coffee.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background: white;
            padding: 40px 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .register-box img {
            width: 150px;
            height: 150px;
            margin-bottom: 10px;
        }

        .register-box h2 {
            margin: 0 0 10px 0;
            color: #2e7d32;
            font-size: 24px;
        }

        .register-box p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #43a047;
        }

        .login-link {
            margin-top: 12px;
            font-size: 0.9em;
            color: #555;
        }

        .login-link a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .register-box {
                margin: 0 20px;
            }
        }
    </style>
</head>

<body>
    <div class="register-box">
        <img src="/storage/images/logo-coffee1.png" alt="Logo Coffee shop">
        <h2>Daftar Akun</h2>
        <p>Silakan isi data untuk registrasi</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login.form') }}">Login</a>
        </div>
    </div>
</body>

</html>
@endsection