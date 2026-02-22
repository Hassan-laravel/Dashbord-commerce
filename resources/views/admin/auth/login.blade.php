<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ config('language.supported.' . app()->getLocale() . '.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('dashboard.auth.login_title') }} - {{ config('app.name') }}</title>

    {{-- Call style files based on text direction --}}
    @if (config('language.supported.' . app()->getLocale() . '.dir') == 'rtl')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login-card shadow-sm p-4">
                <div class="card-body">
                    {{-- Logo or Title --}}
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-primary">{{ config('app.name') }}</h4>
                        <p class="text-muted">{{ __('dashboard.auth.login_title') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('dashboard.general.email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('dashboard.general.password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('dashboard.auth.remember_me') }}
                            </label>
                        </div>

                        {{-- Login Button --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('dashboard.auth.login_btn') }}
                            </button>
                        </div>

                        {{-- Forgot Password Link (Optional) --}}
                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="btn btn-link text-decoration-none small" href="{{ route('password.request') }}">
                                    {{ __('dashboard.auth.forgot_password') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Footer and Language Switcher --}}
                <div class="card-footer bg-white border-0 text-center mt-3">
                    <small class="text-muted d-block mb-2">
                        &copy; {{ date('Y') }} {{ config('app.name') }}
                    </small>

                    {{-- Language Switching Logic --}}
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        @foreach(config('language.supported') as $key => $lang)
                            <a href="{{ route('switch.language', $key) }}"
                               class="text-decoration-none small {{ app()->getLocale() == $key ? 'fw-bold text-dark' : 'text-primary' }}">
                                {{ $lang['name'] }}
                            </a>
                            @if(!$loop->last)
                                <span class="text-muted small">|</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
