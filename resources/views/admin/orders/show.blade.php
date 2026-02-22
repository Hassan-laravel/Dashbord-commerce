@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Product Details Section --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        {{ __('dashboard.orders.details') }} #{{ $order->number }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">{{ __('dashboard.products.name') }}</th>
                                <th>{{ __('dashboard.products.price') }}</th>
                                <th>{{ __('dashboard.products.quantity') }}</th>
                                <th class="text-end pe-3">{{ __('dashboard.orders.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            {{-- Product Main Image --}}
                                            @if($item->product && $item->product->main_image)
                                                <img src="{{ $item->product->main_image_url }}" class="rounded me-2" width="40" height="40" style="object-fit: cover">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $item->product_name }}</div>
                                                @if($item->product)
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                @else
                                                    {{-- Fallback for deleted products --}}
                                                    <small class="text-danger">({{ __('Deleted Product') }})</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ $item->price }}</td>
                                    <td>x {{ $item->quantity }}</td>
                                    <td class="text-end pe-3 fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">{{ __('Subtotal') }}</td>
                                <td class="text-end pe-3">${{ number_format($order->total_price - $order->shipping_price - $order->tax_price, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">{{ __('Shipping') }}</td>
                                <td class="text-end pe-3">${{ $order->shipping_price }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">{{ __('Total') }}</td>
                                <td class="text-end pe-3 fw-bold fs-5 text-primary">${{ $order->total_price }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Customer Info & Order Status Section --}}
        <div class="col-lg-4">
            {{-- Update Order Status Card --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.orders.status_update') }}</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Order Processing Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.orders.status') }}</label>
                            <select name="status" class="form-select">
                                @foreach(['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ __('dashboard.orders.' . $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.orders.payment_status') }}</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>{{ __('dashboard.orders.unpaid') }}</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>{{ __('dashboard.orders.paid') }}</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>{{ __('dashboard.orders.failed') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">{{ __('dashboard.general.save_changes') }}</button>
                    </form>
                </div>
            </div>

            {{-- Customer Information Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.orders.customer_info') }}</div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-light rounded-circle p-2 me-3">
                            <i class="bi bi-person fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $order->customer_name }}</div>
                            <div class="small text-muted">{{ $order->user ? __('Registered Customer') : __('Guest') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i> {{ $order->customer_email ?? 'N/A' }}</div>
                    <div class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i> {{ $order->customer_phone }}</div>
                    <div class="mb-2"><i class="bi bi-geo-alt me-2 text-muted"></i> {{ $order->customer_city }}, {{ $order->customer_address }}</div>

                    {{-- Customer Notes Section --}}
                    @if($order->notes)
                        <div class="alert alert-warning mt-3 mb-0 small">
                            <strong>{{ __('Notes') }}:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
