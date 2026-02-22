@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-cart-check me-2"></i> {{ __('dashboard.orders.title') }}
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>{{ __('dashboard.orders.number') }}</th>
                        <th>{{ __('dashboard.orders.customer') }}</th>
                        <th>{{ __('dashboard.orders.total') }}</th>
                        <th>{{ __('dashboard.orders.payment_status') }}</th>
                        <th>{{ __('dashboard.orders.status') }}</th>
                        <th>{{ __('dashboard.orders.date') }}</th>
                        <th class="text-end pe-3">{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none">
                                    {{ $order->number }}
                                </a>
                            </td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td class="fw-bold">${{ $order->total_price }}</td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">{{ __('dashboard.orders.paid') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('dashboard.orders.unpaid') }}</span>
                                @endif
                            </td>
                            <td>
                                @switch($order->status)
                                    @case('completed') <span class="badge bg-success">{{ __('dashboard.orders.completed') }}</span> @break
                                    @case('pending') <span class="badge bg-warning text-dark">{{ __('dashboard.orders.pending') }}</span> @break
                                    @case('cancelled') <span class="badge bg-danger">{{ __('dashboard.orders.cancelled') }}</span> @break
                                    @default <span class="badge bg-info text-dark">{{ __('dashboard.orders.' . $order->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                                {{ __('dashboard.general.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
