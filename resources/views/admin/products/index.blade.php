@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-box-seam me-2"></i> {{ __('dashboard.products.title') }}
        </h5>
        @can('manage-products')
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> {{ __('dashboard.products.add_new') }}
        </a>
        @endcan
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>{{ __('dashboard.products.main_image') }}</th>
                        <th>{{ __('dashboard.products.name') }}</th>
                        <th>{{ __('dashboard.categories.title') }}</th>
                        <th>{{ __('dashboard.products.price') }}</th>
                        <th>{{ __('dashboard.products.quantity') }}</th>
                        <th>{{ __('dashboard.products.status') }}</th>
                        <th class="text-end pe-3">{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ $product->main_image_url }}"
                                         class="rounded shadow-sm"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{-- يتم جلب الاسم حسب اللغة الحالية تلقائياً من الموديل --}}
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">{{ __('dashboard.products.sku') }}: {{ $product->sku ?? '---' }}</small>
                            </td>
                            <td>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($product->discount_price)
                                    <span class="text-decoration-line-through text-muted small">${{ $product->price }}</span>
                                    <div class="fw-bold text-danger">${{ $product->discount_price }}</div>
                                @else
                                    <span class="fw-bold">${{ $product->price }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $product->quantity > 5 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> {{ __('dashboard.products.active') }}</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i> {{ __('dashboard.products.inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @can('manage-products')
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                                {{ __('dashboard.general.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
