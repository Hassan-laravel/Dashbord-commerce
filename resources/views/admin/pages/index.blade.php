@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">{{ __('dashboard.pages.title') }}</h5>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> {{ __('dashboard.pages.add_new') }}
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>{{ __('dashboard.pages.title_label') }}</th>
                    <th>{{ __('dashboard.pages.slug') }}</th>
                    <th>{{ __('dashboard.pages.status') }}</th>
                    <th class="text-end pe-3">{{ __('dashboard.general.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration }}</td>
                        <td>{{ $page->title }}</td>
                        <td><code>/{{ $page->slug }}</code></td>
                        <td>
                            <span class="badge {{ $page->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $page->status ? __('dashboard.products.active') : __('dashboard.products.inactive') }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
