@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-key me-2"></i> {{ __('dashboard.roles.permissions') }}
        </h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
            <i class="bi bi-plus-lg"></i> {{ __('dashboard.general.add_new') }}
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>{{ __('dashboard.permissions.translated_name') }}</th>
                        <th>{{ __('dashboard.permissions.system_name') }}</th>
                        <th class="text-end pe-3">{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td class="fw-bold">

                                @php $transKey = 'dashboard.permissions.' . $permission->name; @endphp
                                {{ Lang::has($transKey) ? __($transKey) : __('dashboard.permissions.no_translation') }}
                            </td>
                            <td><code class="bg-light px-2 py-1 rounded text-primary">{{ $permission->name }}</code></td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal{{ $permission->id }}">
                                        <i class="bi bi-pencil"></i> {{ __('dashboard.general.edit') }}
                                    </button>

                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@foreach ($permissions as $permission)
    <div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="modal-content shadow-lg">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ __('dashboard.general.edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('dashboard.permissions.permission_system_name') }}</label>
                        <input type="text" name="name" value="{{ $permission->name }}" class="form-control" required>
                        <div class="form-text text-danger mt-2 small">
                            <i class="bi bi-exclamation-triangle"></i> {{ __('dashboard.permissions.edit_warning') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.general.close') }}</button>
                    <button type="submit" class="btn btn-primary px-4">{{ __('dashboard.general.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="modal-content shadow-lg">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ __('dashboard.general.add_new') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('dashboard.permissions.permission_system_name') }}</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('dashboard.permissions.placeholder_name') }}" required>
                    <div class="form-text small">{{ __('dashboard.permissions.name_help_text') }}</div>
                </div>
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    {!! __('dashboard.permissions.translation_hint') !!}
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.general.cancel') }}</button>
                <button type="submit" class="btn btn-success px-4">{{ __('dashboard.general.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
