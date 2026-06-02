<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />

    {{-- Original admin template assets (light theme) --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    {{-- loader --}}
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />

    <title>Sign In · Animestudio</title>

    {{-- Light-theme polish for the login card only --}}
    <style>
        .authentication-content {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background:
                radial-gradient(60% 50% at 10% 10%, rgba(13,110,253,0.08) 0%, rgba(255,255,255,0) 60%),
                radial-gradient(45% 45% at 90% 90%, rgba(13,110,253,0.06) 0%, rgba(255,255,255,0) 60%),
                #f5f7fb;
            padding: 24px 0;
        }
        .auth-card-wrap {
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
        }
        .auth-card-wrap .card {
            border: 0 !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.10), 0 6px 18px rgba(15, 23, 42, 0.05);
        }
        .auth-card-wrap .card-body {
            padding: 44px 40px !important;
        }
        @media (max-width: 575.98px) {
            .auth-card-wrap .card-body { padding: 32px 24px !important; }
        }

        .login-icon-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }
        .login-icon {
            width: 78px; height: 78px;
            border-radius: 22px;
            background: linear-gradient(135deg, #b8e4ff 0%, #a2c9f4 100%) !important;
            color: #0d6efd;
            border: 1px solid #dbeafe;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem;
            line-height: 1;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.12);
        }
        .login-icon i { display: inline-block; line-height: 1; }
        .login-icon i::before { vertical-align: middle; }

        .auth-card-wrap .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            margin-bottom: 6px;
        }
        .auth-card-wrap .card-text {
            color: #64748b;
            text-align: center;
            margin-bottom: 28px !important;
        }

        .auth-card-wrap .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .auth-card-wrap .form-control.radius-30 {
            border-radius: 12px !important;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding-top: 12px;
            padding-bottom: 12px;
            font-size: 0.95rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }
        .auth-card-wrap .form-control.radius-30:focus {
            border-color: #0d6efd;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
        }
        .auth-card-wrap .search-icon i {
            color: #94a3b8;
        }

        .pass-toggle {
            position: absolute;
            top: 50%; right: 12px;
            transform: translateY(-50%);
            background: none; border: 0;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
            line-height: 1;
        }
        .pass-toggle:hover { color: #0d6efd; }

        .auth-meta {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.86rem;
            margin-top: 4px;
        }
        .auth-meta .form-check-label { color: #334155; }
        .auth-meta a { color: #0d6efd; font-weight: 600; text-decoration: none; }
        .auth-meta a:hover { text-decoration: underline; }

        .auth-card-wrap .btn-primary.radius-30 {
            border-radius: 12px !important;
            padding: 12px 18px;
            font-weight: 600;
            letter-spacing: 0.2px;
            color: #0c4a8a !important;
            background: linear-gradient(135deg, #b8e4ff 0%, #a2c9f4 100%) !important;
            border: 1px solid #bfdbfe !important;
            box-shadow: 0 8px 18px rgba(13, 110, 253, 0.10);
            transition: transform 0.12s ease, box-shadow 0.12s ease, background 0.15s ease;
        }
        .auth-card-wrap .btn-primary.radius-30:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #cfe2ff 0%, #ddd6fe 100%) !important;
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.16);
        }
        .auth-card-wrap .btn-primary.radius-30 i { color: #0d6efd; }

        .auth-error {
            display: flex; align-items: center; gap: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.88rem;
        }

        .auth-foot {
            margin-top: 26px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.82rem;
        }
    </style>
</head>

<body>

<!--start wrapper-->
<div class="wrapper">

    <!--start content-->
    <main class="authentication-content">
        <div class="container-fluid">
            <div class="auth-card-wrap">
                <div class="card overflow-hidden">
                    <div class="card-body">

                        {{-- ===== Top icon ===== --}}
                        <div class="login-icon-wrap">
                            <span class="login-icon"><i class="bi bi-shield-lock-fill"></i></span>
                        </div>

                        <h5 class="card-title">Sign in to your account</h5>
                        <p class="card-text">Enter your credentials to access the admin dashboard.</p>

                        <form class="form-body" method="POST" action="{{ url('authenticate') }}" autocomplete="off">
                            @csrf
                            <div class="row g-3">

                                <div class="col-12">
                                    <label for="emailAddress" class="form-label">Email Address</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                                        <input type="email" class="form-control radius-30 ps-5" id="emailAddress"
                                               name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="choosePassword" class="form-label">Password</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                                        <input type="password" class="form-control radius-30 ps-5 pe-5" id="choosePassword"
                                               name="password" placeholder="Enter your password" required>
                                        <button type="button" class="pass-toggle" id="togglePass" aria-label="Show password">
                                            <i class="bi bi-eye-fill" id="togglePassIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                @if($errors->has('err'))
                                    <div class="col-12">
                                        <div class="auth-error" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <span>{{ $errors->first('err') }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="auth-meta">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        {{-- <a href="#">Forgot password?</a> --}}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary radius-30">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>

                        <div class="auth-foot">
                            Protected admin area &middot; &copy; {{ date('Y') }} Animestudio
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end page main-->

</div>
<!--end wrapper-->

<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/pace.min.js') }}"></script>

<script>
    (function () {
        var pwd  = document.getElementById('choosePassword');
        var btn  = document.getElementById('togglePass');
        var icon = document.getElementById('togglePassIcon');
        if (!pwd || !btn) return;
        btn.addEventListener('click', function () {
            var isPwd = pwd.type === 'password';
            pwd.type = isPwd ? 'text' : 'password';
            icon.className = isPwd ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
            btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
        });
    })();
</script>

</body>
</html>
