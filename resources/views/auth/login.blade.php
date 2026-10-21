<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | JMS Operasional</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --ink: #20312f;
            --muted: #7b8781;
            --line: #e1e6df;
            --paper: #f2f3ef;
            --white: #fff;
            --forest: #173a36;
            --forest-deep: #102c29;
            --mint: #dce9de;
            --green: #327966;
            --coral: #d57b5b;
            --gold: #d5ad69;
            --font-body: 'Manrope', 'Segoe UI', sans-serif;
            --font-display: 'DM Serif Display', Georgia, serif;
            --font-mono: 'DM Mono', Consolas, monospace;
        }

        * { box-sizing: border-box; }
        html { min-width: 320px; background: var(--paper); }
        body { margin: 0; color: var(--ink); font-family: var(--font-body); font-size: 13px; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button, summary, select { cursor: pointer; }
        ::selection { color: var(--white); background: var(--green); }

        .eyebrow, .metric-label, .nav-caption, .sidebar-note p, .mono-note, .topbar-meta, .data-table th, .master-category, .snapshot-label, .page-footer, .intro-foot, .login-intro .eyebrow { font-family: var(--font-mono); font-size: 9px; font-weight: 500; letter-spacing: 0; }
        .eyebrow { margin: 0 0 11px; color: var(--green); }
        .brand { display: inline-flex; align-items: center; width: fit-content; color: var(--ink); }
        .brand strong, .brand small { display: block; }
        .brand strong { font-size: 13px; font-weight: 800; }
        .brand small { margin-top: 2px; font-family: var(--font-mono); font-size: 8px; }
        .brand-light { color: #f6f4e9; }
        .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; gap: 12px; border: 1px solid transparent; padding: 0 16px; font-size: 11px; font-weight: 700; transition: background .18s ease, color .18s ease, transform .18s ease; }
        .button:hover { transform: translateY(-1px); }
        .button-primary { color: #fff; background: var(--forest); }
        .button-primary:hover { background: var(--forest-deep); }
        .button-secondary { min-height: 36px; color: var(--ink); border-color: var(--line); background: var(--white); }
        .button-secondary:hover, .button-outline:hover { border-color: var(--green); }
        .button-outline { color: var(--forest); border-color: #bbc9c1; background: transparent; }
        .button-wide { width: 100%; min-height: 49px; justify-content: space-between; padding: 0 18px; }
        .button-plus { font-size: 18px; font-weight: 400; line-height: 1; }
        .icon-button { display: inline-grid; width: 34px; height: 34px; place-items: center; border: 1px solid transparent; color: inherit; background: transparent; font-size: 19px; }
        .icon-button:hover { border-color: var(--line); background: #fff; }
        .danger-action { color: #b7614f; }
        .danger-action:disabled { cursor: not-allowed; opacity: .32; }
        .danger-action:disabled:hover { border-color: transparent; background: transparent; }
        .text-link { display: inline-flex; align-items: center; gap: 9px; color: var(--green); font-size: 10px; font-weight: 800; }
        .text-link:hover { color: var(--coral); }
        .login-page { min-height: 100vh; background: #f7f7f3; }
        .login-layout { display: grid; min-height: 100vh; grid-template-columns: minmax(350px, .92fr) 1.08fr; }
        .login-intro { position: relative; display: flex; min-height: 100vh; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 38px 48px 30px; color: #f6f4e9; background: var(--forest); }
        .login-intro::before { position: absolute; right: -180px; bottom: -230px; width: 540px; height: 540px; border: 1px solid rgba(224, 232, 214, .14); border-radius: 50%; content: ''; }
        .login-intro::after { position: absolute; right: -85px; bottom: -135px; width: 350px; height: 350px; border: 1px solid rgba(224, 232, 214, .14); border-radius: 50%; content: ''; }
        .login-intro > * { position: relative; z-index: 1; }
        .intro-copy { max-width: 470px; margin: 0 auto; padding: 60px 0; }
        .intro-copy .eyebrow { color: #9ab7a6; }
        .intro-copy h1 { margin: 0; font-family: var(--font-display); font-size: clamp(47px, 6vw, 76px); font-weight: 400; line-height: .98; }
        .intro-copy h1 em { color: #e2b775; font-weight: 400; }
        .intro-description { max-width: 345px; margin: 24px 0 0; color: #d0d9cf; font-size: 13px; line-height: 1.9; }
        .intro-foot { display: flex; justify-content: space-between; color: #98aea2; font-size: 8px; }
        .login-panel { display: grid; min-height: 100vh; place-items: center; padding: 48px; }
        .login-form-wrap { width: min(100%, 390px); }
        .login-form-wrap .eyebrow { margin-bottom: 9px; }
        .login-form-wrap h2 { margin: 0; font-family: var(--font-display); font-size: 36px; font-weight: 400; }
        .form-lead { margin: 9px 0 30px; color: var(--muted); font-size: 12px; }
        .login-form { display: flex; flex-direction: column; }
        .login-form > label:not(.remember-row), .field { color: #495951; font-size: 10px; font-weight: 700; }
        .login-form input:not([type=checkbox]), .field input, .field select, .field textarea, .filter-field select, .inline-search input { width: 100%; min-height: 43px; border: 1px solid #dce2db; border-radius: 0; outline: none; padding: 0 12px; color: var(--ink); background: #fff; font-size: 11px; }
        .login-form input:not([type=checkbox]) { margin: 8px 0 18px; }
        input:focus, select:focus, textarea:focus { border-color: #5e9480 !important; box-shadow: 0 0 0 3px rgba(50, 121, 102, .1); }
        input::placeholder, textarea::placeholder { color: #9aa59e; }
        .label-row { display: flex; justify-content: space-between; }
        .remember-row { display: flex; align-items: center; gap: 9px; margin: -2px 0 21px; color: #727f77; font-size: 10px; }
        .remember-row input { width: 14px; height: 14px; accent-color: var(--green); }
        .login-footnote { margin: 19px 0 0; color: #929d96; font-size: 10px; text-align: center; }
        .field-error { margin: -13px 0 14px; color: #b64f43; font-size: 10px; }
        .mobile-brand { display: none; }

        @media (max-width: 850px) {
            .login-layout { grid-template-columns: .85fr 1.15fr; }
            .login-intro { padding-right: 30px; padding-left: 30px; }
            .login-panel { padding: 32px; }
        }
        @media (max-width: 620px) {
            .login-layout { display: block; background: #f7f7f3; }
            .login-intro { min-height: 250px; padding: 21px 23px 18px; }
            .login-intro > .brand { display: none; }
            .intro-copy { margin: 0; padding: 27px 0 29px; }
            .intro-copy .eyebrow { margin-bottom: 8px; font-size: 8px; }
            .intro-copy h1 { font-size: 48px; }
            .intro-description { max-width: 305px; margin-top: 12px; font-size: 10px; line-height: 1.6; }
            .intro-foot { font-size: 7px; }
            .login-panel { min-height: auto; padding: 31px 24px 38px; }
            .mobile-brand { display: inline-flex; margin-bottom: 24px; }
            .login-form-wrap h2 { font-size: 31px; }
            .form-lead { margin-bottom: 22px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        body { font-size: 17px; }
        .eyebrow, .intro-copy .eyebrow { font-size: 13px; }
        .brand strong { font-size: 17px; }
        .brand small { font-size: 12px; }
        .button { font-size: 15px; }
        .text-link { font-size: 14px; }
        .intro-description { font-size: 17px; }
        .intro-foot { font-size: 12px; }
        .form-lead { font-size: 16px; }
        .login-form > label:not(.remember-row) { font-size: 15px; }
        .login-form input:not([type=checkbox]) { font-size: 16px; }
        .remember-row { font-size: 15px; }
        .login-footnote, .field-error { font-size: 14px; }
    </style>
</head>
<body class="login-page">
    <main class="login-layout">
        <section class="login-intro">
            <a class="brand brand-light" href="{{ route('login') }}">
                <span><strong>JMS</strong><small>OPERASIONAL</small></span>
            </a>
            <div class="intro-copy">
                <p class="eyebrow">LEGAL &amp; PARTNERSHIP OFFICE</p>
                <h1>Kerja sama yang<br><em>tertata.</em></h1>
                <p class="intro-description">Satu ruang kerja untuk mengelola draft kerja sama operasional JMS dan rumah sakit mitra.</p>
            </div>
            <div class="intro-foot"><span>DOCUMENT CONTROL</span><span>JMS · 2026</span></div>
        </section>
        <section class="login-panel">
            <div class="login-form-wrap">
                <div class="mobile-brand brand">
                    <span><strong>JMS</strong><small>OPERASIONAL</small></span>
                </div>
                <p class="eyebrow">SELAMAT DATANG KEMBALI</p>
                <h2>Masuk ke ruang kerja</h2>
                <p class="form-lead">Gunakan akun internal Anda untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login.store') }}" class="login-form">
                    @csrf
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" placeholder="nama@perusahaan.co.id" required autofocus>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror

                    <div class="label-row"><label for="password">Kata sandi</label></div>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required>
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror

                    <label class="remember-row"><input type="checkbox" name="remember" value="1"><span>Ingat saya di perangkat ini</span></label>
                    <button class="button button-primary button-wide" type="submit">Masuk <span aria-hidden="true">↗</span></button>
                </form>
                <p class="login-footnote">Akses terbatas untuk tim administrasi JMS.</p>
            </div>
        </section>
    </main>
</body>
</html>