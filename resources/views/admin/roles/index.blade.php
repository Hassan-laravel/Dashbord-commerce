@extends('admin.layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('dashboard.roles.title') }}</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                {{ __('dashboard.general.add_new') }}
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('dashboard.general.name') }}</th>
                        <th>{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-info">{{ $role->name }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="bi bi-pencil"></i> {{ __('dashboard.general.edit') }}
                                </button>

                                @if ($role->name !== 'Super Admin')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> {{ __('dashboard.general.delete') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted">{{ __('dashboard.roles.system_role') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{--
        الإصلاح: نقل المودال الخاص بالتعديل إلى خارج الجدول
        وداخل حلقة foreach منفصلة
    --}}
    @foreach ($roles as $role)
        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg bg-light shadow-sm rounded">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('dashboard.roles.edit') }}: {{ $role->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.roles.name') }}</label>
                            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required {{ $role->name == 'Super Admin' ? 'readonly' : '' }}>
                        </div>

                        <label class="form-label fw-bold">{{ __('dashboard.roles.permissions') }}</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check p-2 border rounded shadow-sm bg-light">
                                    <input class="form-check-input ms-2 me-2" type="checkbox" name="permissions[]"
                                           value="{{ $permission->name }}"
                                           id="edit-perm-{{ $role->id }}-{{ $permission->id }}"
                                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit-perm-{{ $role->id }}-{{ $permission->id }}">
                                        {{ __('dashboard.permissions.' . $permission->name) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
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

    {{-- مودال الإضافة (Add Modal) --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.roles.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.roles.add_new') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">{{ __('dashboard.roles.name') }}</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="{{ __('dashboard.roles.placeholder') }}" required>
                    </div>

                    <hr>
                    <label class="form-label fw-bold mb-3">{{ __('dashboard.roles.permissions') }}</label>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check p-2 border rounded shadow-sm">
                                    <input class="form-check-input ms-2 me-2" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                        {{ __('dashboard.permissions.' . $permission->name) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('dashboard.general.cancel') }}</button>
                    <button type="submit" class="btn btn-success px-4">{{ __('dashboard.general.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
