@extends('website.layout')

@section('title', 'My Profile - AnimeStudio Learning Platform')

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
        --green-soft: rgba(34, 197, 94, 0.15);
        --blue: #3b82f6;
        --blue-soft: rgba(59, 130, 246, 0.15);
        --rose: #f43f5e;
        --rose-soft: rgba(244, 63, 94, 0.15);
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

    /* ===== PAGE SHELL ===== */
    .profile-section {
        background: var(--bg-deep);
        min-height: calc(100vh - 80px);
        padding: 32px 0 60px;
    }

    /* ===== HERO HEADER ===== */
    .profile-header {
        position: relative;
        background: linear-gradient(135deg, #161c27 0%, #1c2330 50%, #232b39 100%);
        color: #fff;
        padding: 32px;
        border-radius: 22px;
        margin-bottom: 28px;
        overflow: hidden;
        border: 1px solid var(--line);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
    }
    .profile-header::before,
    .profile-header::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        opacity: 0.2;
        pointer-events: none;
    }
    .profile-header::before {
        width: 240px; height: 240px;
        background: var(--accent);
        top: -100px; right: -40px;
    }
    .profile-header::after {
        width: 180px; height: 180px;
        background: #8b5cf6;
        bottom: -80px; left: 20%;
    }
    .profile-header .row { position: relative; z-index: 1; }
    .profile-header h2 {
        font-weight: 800;
        margin-bottom: 6px;
        font-size: 1.9rem;
        letter-spacing: -0.5px;
        color: var(--txt-primary);
    }
    .profile-header h2 i { color: var(--accent-2); }
    .profile-header p { color: var(--txt-secondary); margin: 0; font-size: 0.95rem; }

    .profile-header .btn-light {
        background: rgba(255,255,255,0.16);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        padding: 9px 18px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s ease;
    }
    .profile-header .btn-light:hover {
        background: rgba(255,255,255,0.28);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ===== PROFILE CARDS ===== */
    .profile-card {
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 26px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.18);
        margin-bottom: 24px;
    }
    .profile-card h4 {
        color: var(--txt-primary);
        margin-bottom: 20px;
        font-weight: 700;
        border-bottom: 1px solid var(--line);
        padding-bottom: 14px;
        font-size: 1.1rem;
        letter-spacing: -0.2px;
    }
    .profile-card h4 i {
        color: var(--accent);
        margin-right: 10px;
    }

    /* ===== FORM CONTROLS ===== */
    .form-group { margin-bottom: 18px; }
    .form-label {
        font-weight: 600;
        color: var(--txt-secondary);
        margin-bottom: 8px;
        font-size: 0.88rem;
    }
    .form-label .text-danger { color: var(--rose) !important; }

    .form-control {
        background: var(--bg-panel) !important;
        border: 1px solid var(--line) !important;
        color: var(--txt-primary) !important;
        border-radius: 12px !important;
        padding: 11px 14px !important;
        font-size: 0.93rem !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control::placeholder { color: var(--txt-muted); }
    .form-control:focus {
        background: var(--bg-panel) !important;
        border-color: var(--accent) !important;
        color: var(--txt-primary) !important;
        box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.15) !important;
        outline: none;
    }
    .form-control.is-invalid {
        border-color: var(--rose) !important;
        background: var(--bg-panel) !important;
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.15) !important;
    }
    .invalid-feedback {
        color: #fb7185;
        font-size: 0.82rem;
        margin-top: 6px;
    }
    /* dark calendar icon for date input */
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(0.7); cursor: pointer; }

    /* ===== BUTTONS ===== */
    .btn-update {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        color: #fff;
        border: none;
        padding: 11px 26px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.25s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 16px rgba(247, 147, 30, 0.3);
    }
    .btn-update:hover {
        background: linear-gradient(135deg, var(--accent-2) 0%, var(--accent) 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(247, 147, 30, 0.4);
        color: #fff;
    }

    .btn-cancel {
        background: var(--bg-panel);
        color: var(--txt-secondary);
        border: 1px solid var(--line-2);
        padding: 11px 26px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-cancel:hover {
        background: var(--bg-card-hover);
        color: var(--txt-primary);
        transform: translateY(-1px);
    }

    /* ===== INFO DISPLAY (account info) ===== */
    .info-display {
        background: var(--bg-panel);
        padding: 14px 16px;
        border-radius: 12px;
        border-left: 3px solid var(--accent);
        border: 1px solid var(--line);
        border-left: 3px solid var(--accent);
        margin-bottom: 12px;
    }
    .info-display label {
        font-weight: 600;
        color: var(--txt-muted);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }
    .info-display p {
        color: var(--txt-primary);
        font-size: 0.95rem;
        margin: 0;
        font-weight: 600;
    }
    .info-display .badge.bg-success {
        background: var(--green-soft) !important;
        color: #4ade80 !important;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }
    .info-display .badge.bg-danger {
        background: var(--rose-soft) !important;
        color: #fb7185 !important;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }

    /* ===== PASSWORD TOGGLE ===== */
    .password-toggle { position: relative; }
    .password-toggle .toggle-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--txt-muted);
        transition: color 0.2s ease;
    }
    .password-toggle .toggle-icon:hover { color: var(--accent); }

    /* ===== ALERTS ===== */
    .alert {
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 20px;
        border: 1px solid var(--line);
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.08) !important;
        color: #4ade80 !important;
        border-color: rgba(34, 197, 94, 0.3) !important;
    }
    .alert-success i { color: var(--green); margin-right: 6px; }
    .alert-danger {
        background: rgba(244, 63, 94, 0.08) !important;
        color: #fb7185 !important;
        border-color: rgba(244, 63, 94, 0.3) !important;
    }
    .alert-danger i { color: var(--rose); margin-right: 6px; }
    .alert-danger strong { color: #fda4af; }
    .alert-danger ul { color: #fb7185; margin: 8px 0 0; padding-left: 22px; }
    .alert-info {
        background: rgba(59, 130, 246, 0.08) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }
    .alert-info i { color: var(--blue); margin-right: 6px; }
    .btn-close { filter: invert(0.85); opacity: 0.8; }
    .btn-close:hover { opacity: 1; }

    /* ===== PASSWORD REQUIREMENTS ===== */
    .password-requirements {
        font-size: 0.8rem;
        color: var(--txt-muted);
        margin-top: 8px;
        padding-left: 18px;
    }
    .password-requirements li { margin-bottom: 3px; }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    .fade-in { animation: fadeUp 0.4s ease both; }

    @media (max-width: 768px) {
        .profile-section { padding: 20px 0 40px; }
        .profile-header { padding: 22px; border-radius: 18px; }
        .profile-header h2 { font-size: 1.4rem; }
        .profile-card { padding: 20px; }
        .profile-card h4 { font-size: 1rem; }
        .btn-update, .btn-cancel { width: 100%; justify-content: center; margin-bottom: 8px; }
    }
</style>
@endpush

@section('content')
<section class="profile-section">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header fade-in">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-user-circle"></i> My Profile</h2>
                    <p class="mb-0">Manage your personal information and account settings</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if(Session::has('message'))
            @php
                $message = explode('#', Session::get('message'));
                $type = $message[0] ?? 'info';
                $text = $message[1] ?? '';
            @endphp
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                <i class="fas fa-{{ $type === 'success' ? 'check-circle' : 'exclamation-triangle' }}"></i>
                {{ $text }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Personal Information Form -->
            <div class="col-md-6">
                <div class="profile-card">
                    <h4><i class="fas fa-user-edit"></i> Personal Information</h4>

                    <form action="{{ route('student.profile.update') }}" method="POST" id="profileForm">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $student->student_name ?? '') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $student->email ?? '') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('mobile') is-invalid @enderror"
                                   id="mobile"
                                   name="mobile"
                                   value="{{ old('mobile', $student->mobile ?? '') }}"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date"
                                   class="form-control @error('date_of_birth') is-invalid @enderror"
                                   id="date_of_birth"
                                   name="date_of_birth"
                                   value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="place" class="form-label">Place</label>
                            <input type="text"
                                   class="form-control @error('place') is-invalid @enderror"
                                   id="place"
                                   name="place"
                                   value="{{ old('place', $student->place ?? '') }}">
                            @error('place')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                           <!-- <button type="submit" class="btn-update">
                                <i class="fas fa-save"></i> Update Profile
                            </button> -->
                            
                            <a href="{{ route('student.dashboard') }}" class="btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="col-md-6">
              {{--  <div class="profile-card">
                    <h4><i class="fas fa-key"></i> Change Password</h4>

                    <form action="{{ route('student.profile.update') }}" method="POST" id="passwordForm">
                        @csrf

                        <!-- Hidden fields to maintain other data -->
                        <input type="hidden" name="name" value="{{ $student->student_name ?? '' }}">
                        <input type="hidden" name="email" value="{{ $student->email ?? '' }}">
                        <input type="hidden" name="mobile" value="{{ $student->mobile ?? '' }}">
                        <input type="hidden" name="date_of_birth" value="{{ $student->date_of_birth ?? '' }}">
                        <input type="hidden" name="place" value="{{ $student->place ?? '' }}">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Leave password fields empty if you don't want to change your password.
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <div class="password-toggle">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password">
                                <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <ul class="password-requirements">
                                <li>Minimum 6 characters</li>
                                <li>Must match confirmation password</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="password-toggle">
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation">
                                <i class="fas fa-eye toggle-icon" onclick="togglePassword('password_confirmation')"></i>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-update">
                                <i class="fas fa-lock"></i> Update Password
                            </button>
                            <button type="reset" class="btn-cancel">
                                <i class="fas fa-undo"></i> Clear
                            </button>
                        </div>
                    </form>
                </div>
--}}
                <!-- Account Information Display -->
                <div class="profile-card">
                    <h4><i class="fas fa-info-circle"></i> Account Information</h4>

                    <div class="info-display">
                        <label>Student ID</label>
                        <p>#{{ $student->id ?? 'N/A' }}</p>
                    </div>

                    <div class="info-display">
                        <label>Account Status</label>
                        <p>
                            @if(($student->status ?? 0) == 1)
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="info-display">
                        <label>Member Since</label>
                        <p>{{ \Carbon\Carbon::parse($student->created_at ?? now())->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling;

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Form validation for password form
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;

        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        }
    });

    // Mobile number validation
    document.getElementById('mobile').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
