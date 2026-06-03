@extends('website.layout')

@section('title', 'Student Login - Learning Platform')

@push('styles')
<style>
    :root {
        --bg-deep: #0a0e17;
        --bg-panel: #11161f;
        --bg-card: #161c27;
        --bg-card-hover: #1c2330;
        --line: #232b39;
        --line-2: #2a3343;
        --txt-primary: #e6e9ef;
        --txt-secondary: #9aa3b2;
        --txt-muted: #5c6677;
        --accent: #f7931e;
        --accent-2: #ffbb55;
        --green: #22c55e;
        --rose: #f43f5e;
    }

    body { background: var(--bg-deep); color: var(--txt-primary); }

    /* ===== NAVBAR DARK OVERRIDE ===== */
    .navbar.fixed-top {
        background: rgba(17, 22, 31, 0.92) !important;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #1c2330;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .navbar .navbar-brand, .navbar .navbar-brand:hover { color: #f7931e !important; }
    .navbar-nav .nav-link, .navbar-nav .nav-link:focus { color: #c7ccd6 !important; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #f7931e !important; }
    .navbar .btn-primary-custom {
        background: #f7931e !important; border-color: #f7931e !important; color: #fff !important;
        box-shadow: 0 4px 14px rgba(247, 147, 30, 0.3);
    }
    .navbar .btn-primary-custom:hover { background: #ffbb55 !important; border-color: #ffbb55 !important; }
    .navbar .dropdown-menu { background: #161c27; border: 1px solid #232b39; box-shadow: 0 12px 30px rgba(0,0,0,0.5); }
    .navbar .dropdown-item { color: #c7ccd6; }
    .navbar .dropdown-item:hover, .navbar .dropdown-item:focus { background: #1c2330; color: #f7931e; }
    .navbar .dropdown-item.text-danger { color: #fb7185 !important; }
    .navbar .dropdown-divider { border-top-color: #232b39; }
    .navbar-toggler { border-color: #232b39; padding: 4px 8px; }
    .navbar-toggler-icon { filter: invert(0.9); }

    /* ===== FOOTER DARK OVERRIDE ===== */
    .footer { background: #0a0e17 !important; color: #c7ccd6 !important; border-top: 1px solid #1c2330; }
    .footer h5 { color: #fff; font-weight: 700; }
    .footer p, .footer li, .footer span { color: #9aa3b2; }
    .footer a { color: #9aa3b2; text-decoration: none; }
    .footer a:hover { color: #f7931e; }
    .footer .contact-info li i { color: #f7931e !important; }
    .footer hr { background-color: #1c2330 !important; opacity: 1; }
    .footer .social-links a { background: rgba(247,147,30,0.1); color: #f7931e; border: 1px solid rgba(247,147,30,0.2); }
    .footer .social-links a:hover { background: #f7931e; color: #fff; border-color: #f7931e; }

    /* ===== LOGIN PAGE ===== */
    .login-section {
        position: relative;
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
        background: var(--bg-deep);
        padding: 60px 0;
        overflow: hidden;
    }
    /* decorative gradient blobs */
    .login-section::before,
    .login-section::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.18;
        pointer-events: none;
    }
    .login-section::before {
        width: 360px; height: 360px;
        background: var(--accent);
        top: -120px; right: -80px;
    }
    .login-section::after {
        width: 320px; height: 320px;
        background: #8b5cf6;
        bottom: -120px; left: -60px;
    }

    .login-card {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 22px;
        padding: 40px 36px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        z-index: 1;
        animation: fadeRise 0.4s ease both;
    }
    @keyframes fadeRise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .login-icon-wrap {
        width: 72px; height: 72px;
        margin: 0 auto 16px;
        border-radius: 22px;
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 1.9rem;
        box-shadow: 0 10px 28px rgba(247, 147, 30, 0.4);
    }
    .login-header h3 {
        color: var(--txt-primary);
        font-weight: 800;
        margin: 0 0 6px;
        font-size: 1.5rem;
        letter-spacing: -0.4px;
    }
    .login-header .text-muted {
        color: var(--txt-secondary) !important;
        font-size: 0.92rem;
    }

    /* form */
    .form-label {
        font-weight: 600;
        color: var(--txt-secondary);
        margin-bottom: 8px;
        font-size: 0.88rem;
    }
    .form-control {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        color: var(--txt-primary) !important;
        border-radius: 12px !important;
        padding: 12px 14px !important;
        font-size: 0.95rem !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control::placeholder { color: var(--txt-muted); }
    .form-control:focus {
        background: var(--bg-panel) !important;
        border-color: var(--accent) !important;
        color: var(--txt-primary) !important;
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18) !important;
        outline: none;
    }
    .form-control.is-invalid { border-color: var(--rose) !important; }

    .input-group {
        border-radius: 12px;
        overflow: hidden;
    }
    .input-group-text {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        border-right: none !important;
        color: var(--accent) !important;
        border-radius: 12px 0 0 12px !important;
        padding: 12px 14px;
    }
    .input-group .form-control {
        border-left: none !important;
        border-radius: 0 12px 12px 0 !important;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--accent) !important;
    }

    /* form check (remember me) */
    .form-check-input {
        background-color: var(--bg-panel);
        border: 1px solid var(--line-2);
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--accent);
        border-color: var(--accent);
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.18);
    }
    .form-check-label {
        color: var(--txt-secondary);
        font-size: 0.88rem;
        cursor: pointer;
    }

    .forgot-link {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        transition: color 0.2s ease;
    }
    .forgot-link:hover {
        color: var(--accent-2);
        text-decoration: underline;
    }

    /* login button */
    .btn-login {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 13px;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.25s ease;
        width: 100%;
        box-shadow: 0 10px 26px rgba(247, 147, 30, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-login:hover {
        background: linear-gradient(135deg, var(--accent-2) 0%, var(--accent) 100%);
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(247, 147, 30, 0.45);
        color: #fff;
    }

    /* register prompt */
    .register-prompt {
        text-align: center;
        margin-top: 24px;
        padding-top: 22px;
        border-top: 1px solid var(--line);
        color: var(--txt-secondary);
        font-size: 0.9rem;
    }
    .register-prompt a {
        color: var(--accent);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .register-prompt a:hover { color: var(--accent-2); }

    /* alerts */
    .alert {
        border-radius: 14px;
        padding: 13px 18px;
        margin-bottom: 18px;
        border: 1px solid var(--line);
        font-size: 0.9rem;
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.08) !important;
        color: #4ade80 !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }
    .alert-danger {
        background: rgba(244, 63, 94, 0.08) !important;
        color: #fb7185 !important;
        border-color: rgba(244, 63, 94, 0.3) !important;
    }
    .alert-info {
        background: rgba(59, 130, 246, 0.08) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }
    .btn-close { filter: invert(0.85); opacity: 0.8; }
    .btn-close:hover { opacity: 1; }

    .text-danger { color: #fb7185 !important; font-size: 0.82rem; }

    @media (max-width: 575px) {
        .login-card { padding: 28px 22px; border-radius: 18px; }
        .login-icon-wrap { width: 60px; height: 60px; font-size: 1.5rem; border-radius: 18px; }
        .login-header h3 { font-size: 1.3rem; }
    }
</style>
@endpush

@section('content')
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="login-icon-wrap"><i class="fas fa-user-graduate"></i></div>
                            <h3>Student Login</h3>
                            <p class="text-muted">Access your learning dashboard</p>
                        </div>

                        @if(Session::has('message'))
                            @php
                                $message = explode('#', Session::get('message'));
                                $type = $message[0] ?? 'info';
                                $text = $message[1] ?? '';
                            @endphp
                            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                                {{ $text }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Candidate Id</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                    <input type="text"
                                           class="form-control"
                                           id="candidate_id"
                                           name="candidate_id"
                                           placeholder="Enter Your id"
                                           value="{{ old('candidate_id') }}"
                                           maxlength="10"
                                           required>
                                </div>
                                @error('mobile')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{--<div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('student.forgot-password') }}" class="forgot-link">
                                    Forgot Password?
                                </a>
                            </div>--}}

                            <div class="mb-3">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </div>
                        </form>

                        {{--<div class="register-prompt">
                            <p>Don't have an account? <a href="{{ route('student.register') }}">Register Now</a></p>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Auto-format mobile number
    document.getElementById('mobile').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
    });

    // Show/Hide password
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
    }
</script>
@endpush