@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-gear me-2"></i> {{ __('dashboard.customers.edit') }}: {{ $customer->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.customers.name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('dashboard.customers.email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('dashboard.customers.phone') }}</label>
                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.general.password') }} <small class="text-muted">({{ __('dashboard.general.leave_blank') }})</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-check form-switch border rounded p-3 mb-4">
                            <label class="form-check-label fw-bold" for="statusSwitch">{{ __('dashboard.products.status') }} ({{ __('dashboard.products.active') }})</label>
                            <input class="form-check-input float-end" type="checkbox" id="statusSwitch" name="status" value="1" {{ $customer->status ? 'checked' : '' }}>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('dashboard.general.save_changes') }}</button>
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-light border">{{ __('dashboard.general.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
