<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mini ERP — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #111827;
            border-radius: 16px;
            padding: 40px 32px 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            background: #1e293b;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .brand-icon i {
            font-size: 28px;
            color: #3b82f6;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 28px;
        }

        .form-label {
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control {
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 8px;
            color: #ffffff;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            background: #1f2937;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            color: #ffffff;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background: #1f2937;
        }

        .invalid-feedback {
            font-size: 12px;
            margin-top: 4px;
        }

        .form-check-input {
            background: #1f2937;
            border-color: #475569;
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .form-check-label {
            color: #94a3b8;
            font-size: 13px;
        }

        .btn-login {
            width: 100%;
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-login:hover {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .forgot-link {
            color: #94a3b8;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #3b82f6;
        }

        .session-status {
            background: rgba(34, 197, 94, 0.12);
            color: #22c55e;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .footer-text {
            text-align: center;
            color: #475569;
            font-size: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #1e293b;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    <div class="login-card">

        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="brand-icon">
            <i class="bi bi-building"></i>
        </div>

        <div class="text-center">
            <div class="brand-title">Mini ERP</div>
            <div class="brand-subtitle">Business Management Dashboard</div>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" value="{{ old('email') }}"
                    placeholder="Enter your email" required autofocus autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    id="password" name="password"
                    placeholder="Enter your password" required autocomplete="current-password">
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} Mini ERP &mdash; All Rights Reserved
        </div>

    </div>

</body>
</html>
