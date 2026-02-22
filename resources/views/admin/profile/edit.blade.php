@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person-bounding-box me-2"></i> {{ __('dashboard.profile.settings') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Full Name Field --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.general.name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email Address Field --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.general.email') }}</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Password Information Hint --}}
                        <div class="alert alert-info py-2 small">
                            <i class="bi bi-info-circle me-1"></i> {{ __('dashboard.profile.password_hint') }}
                        </div>

                        {{-- New Password Field --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.profile.new_password') }}</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Password Confirmation Field --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('dashboard.profile.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> {{ __('dashboard.general.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
