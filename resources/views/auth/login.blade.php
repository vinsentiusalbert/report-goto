<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Report GoTo</title>
    <meta name="description" content="Masuk ke dashboard Report GoTo">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-brand">
            <div class="brand-mark brand-mark--light" aria-label="Report GoTo">
                <span>GoTo</span><strong>MyAds Report</strong>
            </div>
            <div class="brand-copy">
                <p class="eyebrow">Business Intelligence</p>
                <h1>Report Goto MyAds.</h1>
                <p>Pantau jangkauan audience, delivery, spending, dan performa campaign GoTo MyAds secara ringkas.</p>
            </div>
            <div class="brand-metric">
            </div>
            <div class="login-orb login-orb--one"></div>
            <div class="login-orb login-orb--two"></div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="mobile-brand brand-mark"><span>GoTo</span><strong>MyAds Report</strong></div>
                <p class="eyebrow text-green">Selamat datang kembali</p>
                <h2>Masuk ke dashboard</h2>
                <p class="login-subtitle">Gunakan akun Anda untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login.store') }}" class="login-form">
                    @csrf
                    <label>
                        <span>Alamat email</span>
                        <div class="input-wrap @error('email') input-error @enderror">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4zM4 7l8 6 8-6"/></svg>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="email" autofocus required>
                        </div>
                        @error('email') <small class="error-text">{{ $message }}</small> @enderror
                    </label>

                    <label>
                        <span>Kata sandi</span>
                        <div class="input-wrap @error('password') input-error @enderror">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                            <input id="password" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" data-password-toggle>
                                <svg viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                        @error('password') <small class="error-text">{{ $message }}</small> @enderror
                    </label>

                    <div class="form-options">
                        <label class="check-label"><input type="checkbox" name="remember"><span>Ingat saya</span></label>
                        <span class="muted-link">Lupa kata sandi?</span>
                    </div>

                    <button type="submit" class="primary-button">
                        Masuk ke dashboard
                        <svg viewBox="0 0 24 24"><path d="M5 12h14m-5-5l5 5-5 5"/></svg>
                    </button>
                </form>

                <div class="demo-account">
                    <span>Akun demo</span>
                    <code>admin@reportgoto.id</code>
                    <span class="dot">•</span>
                    <code>password</code>
                </div>
                <p class="login-footer">© {{ date('Y') }} Report GoTo. Internal business dashboard.</p>
            </div>
        </section>
    </main>
</body>
</html>
