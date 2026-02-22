@extends('admin.layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('dashboard.users.title') }}</h5>
        </div>
        <div class="card-body">
            {{-- Add New User Form --}}
            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3 mb-4 p-3 border rounded bg-light">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">{{ __('dashboard.general.name') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('dashboard.general.email') }}</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('dashboard.general.password') }}</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('dashboard.users.role') }}</label>
                    <select name="role" class="form-select">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">{{ __('dashboard.general.save') }}</button>
                </div>
            </form>

            {{-- Users Data Table --}}
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('dashboard.general.name') }}</th>
                        <th>{{ __('dashboard.general.email') }}</th>
                        <th>{{ __('dashboard.users.role') }}</th>
                        <th>{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-secondary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{-- Trigger Modal Button --}}
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal{{ $user->id }}">
                                    <i class="bi bi-pencil"></i> {{ __('dashboard.general.edit') }}
                                </button>

                                {{-- Prevention of Super Admin deletion --}}
                                @if (!$user->hasRole('Super Admin'))
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted ms-2" title="{{ __('dashboard.roles.system_role') }}">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{--
        FIX:
        Modal loop is placed outside the table and the main card.
        This ensures proper Bootstrap rendering and avoids z-index or overflow issues.
    --}}
    @foreach ($users as $user)
        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('dashboard.users.edit') }}: {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.general.name') }}</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.general.email') }}</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.general.password') }}</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="{{ __('dashboard.profile.password_hint') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.users.role') }}</label>
                            <select name="role" class="form-select">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.general.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.general.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
