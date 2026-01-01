<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRODO - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #979797 100%);
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
            min-height: 600px; /* Sedikit lebih tinggi untuk form register yang lebih panjang */
        }

        /* Kolom Kiri (Merah/Gradient) */
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

        /* Kolom Kanan (Form Putih) */
        .login-right {
            padding: 40px 40px; /* Padding sedikit dikurangi vertikalnya agar muat */
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto; /* Antisipasi jika form terlalu panjang di layar kecil */
        }

        .login-right h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-right > p {
            color: #666;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 15px; /* Margin antar input sedikit lebih rapat */
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
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
            font-size: 0.8rem;
            margin-top: 4px;
            display: block;
        }

        .login-btn {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #a02127 0%, #8c1d22 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(160, 33, 39, 0.3);
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(160, 33, 39, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Login Link Section */
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .register-link a {
            color: #a02127;
            text-decoration: none;
            font-weight: 700;
            margin-left: 5px;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #8c1d22;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                height: auto;
            }

            .login-left {
                padding: 40px 30px;
                min-height: 250px; /* Lebih pendek di mobile */
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side (Informasi / Branding) -->
        <div class="login-left">
            <h1>Bergabung Bersama SIPRODO</h1>
            <p>Daftarkan akun Anda untuk mulai mengelola data penelitian dan pengabdian masyarakat.</p>
            <ul class="login-features">
                <li>Akses penuh ke dashboard dosen</li>
                <li>Input data Tri Dharma dengan mudah</li>
                <li>Pantau status verifikasi real-time</li>
                <li>Arsip digital terintegrasi</li>
            </ul>
        </div>

        <!-- Right Side (Form Registrasi) -->
        <div class="login-right">
            <h2>Daftar Akun</h2>
            <p>Lengkapi formulir di bawah ini untuk membuat akun baru.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Nama Lengkap Anda"
                        required 
                        autofocus 
                        autocomplete="name" 
                    />
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="nama@contoh.com"
                        required 
                        autocomplete="username" 
                    />
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        placeholder="Buat kata sandi aman"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Ulangi kata sandi"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" class="login-btn">Daftar Sekarang</button>

                <!-- Login Link -->
                <div class="register-link">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>