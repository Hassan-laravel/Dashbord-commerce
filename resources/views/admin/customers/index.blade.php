@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-people me-2"></i> {{ __('dashboard.customers.title') }}
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>{{ __('dashboard.customers.name') }}</th>
                        <th>{{ __('dashboard.customers.email') }} / {{ __('dashboard.customers.phone') }}</th>
                        <th>{{ __('dashboard.customers.status') }}</th>
                        <th>{{ __('dashboard.customers.registered_at') }}</th>
                        <th class="text-end pe-3">{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $customer->name }}</div>
                            </td>
                            <td>
                                <div><a href="mailto:{{ $customer->email }}" class="text-decoration-none">{{ $customer->email }}</a></div>
                                <small class="text-muted">{{ $customer->phone ?? '---' }}</small>
                            </td>
                            <td>
                                @if($customer->status)
                                    <span class="badge bg-success">{{ __('dashboard.products.active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('dashboard.products.inactive') }}</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                {{ __('dashboard.general.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
