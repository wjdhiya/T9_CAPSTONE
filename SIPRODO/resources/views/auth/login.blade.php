<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRODO - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }

        .login-left {
            background: linear-gradient(135deg, #a02127 0%, #8c1d22 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }

        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .login-left p {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .login-features {
            list-style: none;
        }

        .login-features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .login-features li:before {
            content: "✓";
            display: inline-block;
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            margin-right: 12px;
            font-weight: bold;
        }

        .login-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-right > p {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #a02127;
            box-shadow: 0 0 0 3px rgba(160, 33, 39, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            color: #666;
            cursor: pointer;
            margin: 0;
            font-weight: 500;
        }

        .remember-forgot input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #a02127;
        }

        .remember-forgot a {
            color: #a02127;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .remember-forgot a:hover {
            color: #8c1d22;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #a02127 0%, #8c1d22 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(160, 33, 39, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(160, 33, 39, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .session-status {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
            font-size: 0.9rem;
        }

        .session-status.error {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: #dc2626;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-left {
                padding: 40px 30px;
                min-height: 300px;
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .login-right {
                padding: 40px 30px;
            }

            .login-right h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <h1>SIPRODO</h1>
            <p>Sistem Informasi Penelitian, Publikasi, dan Pengabdian Masyarakat</p>
            <ul class="login-features">
                <li>Kelola data penelitian dengan mudah</li>
                <li>Publikasi dan pantau hasil karya</li>
                <li>Kelola pengabdian masyarakat</li>
                <li>Verifikasi dan laporan real-time</li>
            </ul>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <h2>Masuk</h2>
            <p>Masukkan kredensial Anda untuk melanjutkan</p>

            @if ($errors->any())
                <div class="session-status error">
                    <strong>Gagal Login!</strong> Email atau password salah.
                </div>
            @endif

            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login', absolute: false) }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Masukkan email Anda"
                        required 
                        autofocus 
                        autocomplete="username"
                    />
                    @if ($errors->has('email'))
                        <span class="error-message">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        placeholder="Masukkan password Anda"
                        required 
                        autocomplete="current-password"
                    />
                    @if ($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
