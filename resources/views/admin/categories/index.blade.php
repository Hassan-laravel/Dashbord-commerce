@extends('admin.layouts.app')

@section('title', __('dashboard.categories.title'))

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-tags me-2"></i> {{ __('dashboard.categories.list') }}
        </h5>
        <button type="button" class="btn btn-primary" onclick="openAddModal()">
            <i class="bi bi-plus-lg"></i> {{ __('dashboard.categories.add_new') }}
        </button>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('dashboard.general.image') }}</th>
                        <th>{{ __('dashboard.categories.name') }}</th>
                        <th>{{ __('dashboard.categories.slug') }}</th>
                        <th>{{ __('dashboard.general.status') }}</th>
                        <th class="text-end">{{ __('dashboard.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ $category->image_url }}" width="50" height="50" class="rounded border object-fit-cover">
                            @else
                                <span class="badge bg-light text-secondary border">No Image</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->slug }}</td>
                        <td>
                            @if($category->status)
                                <span class="badge bg-success">{{ __('dashboard.general.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('dashboard.general.inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-primary" onclick="editCategory({{ $category->id }})" title="{{ __('dashboard.general.edit') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('dashboard.general.confirm_delete_msg') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="{{ __('dashboard.general.delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('dashboard.general.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $categories->links() }}</div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">{{ __('dashboard.categories.add_new') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    @php $currentLang = app()->getLocale(); @endphp

                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('dashboard.general.entry_language') }}
                        <strong>{{ config('language.supported.'.$currentLang.'.name') }}</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.categories.name') }}</label>
                                <input type="text" class="form-control" name="{{ $currentLang }}[name]" id="catName" onkeyup="handleTitleChange(this.value)" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.categories.slug') }}</label>
                                <input type="text" class="form-control bg-light" name="{{ $currentLang }}[slug]" id="catSlug">
                                <div class="form-text text-muted small">{{ __('dashboard.general.auto_generated') }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.categories.meta_title') }}</label>
                                <input type="text" class="form-control" name="{{ $currentLang }}[meta_title]" id="catMetaTitle">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.categories.meta_description') }}</label>
                                <textarea class="form-control" name="{{ $currentLang }}[meta_description]" id="catMetaDesc" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-5 border-start">
                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.categories.image') }}</label>
                                <input type="file" class="form-control" name="image" id="catImage" accept="image/*" onchange="previewImage(this)">
                                <div id="imagePreviewContainer" class="mt-2 d-none text-center p-2 bg-light border rounded">
                                    <img src="" id="imgPreview" class="img-fluid rounded" style="max-height: 150px;">
                                    <button type="button" class="btn btn-sm btn-link text-danger d-block mx-auto mt-1" onclick="removeImage()">{{ __('dashboard.general.delete') }}</button>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">{{ __('dashboard.general.status') }}</label>
                                <select class="form-select" name="status" id="categoryStatus">
                                    <option value="1">{{ __('dashboard.general.active') }}</option>
                                    <option value="0">{{ __('dashboard.general.inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.general.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.general.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const currentLocale = "{{ app()->getLocale() }}";

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imgPreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        document.getElementById('catImage').value = '';
        document.getElementById('imagePreviewContainer').classList.add('d-none');
        document.getElementById('imgPreview').src = '';
    }

    function handleTitleChange(text) {
        let slug = text.trim().replace(/\s+/g, '-').replace(/[^\w\u0621-\u064A-]/g, '');
        document.getElementById('catSlug').value = slug;
        document.getElementById('catMetaTitle').value = text;
        if (text.trim() !== '') {
            let keywords = text.trim().replace(/\s+/g, ',');
            document.getElementById('catMetaDesc').value = keywords;
        } else {
            document.getElementById('catMetaDesc').value = '';
        }
    }

    function openAddModal() {
        document.getElementById('categoryForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('categoryForm').action = "{{ route('admin.categories.store') }}";
        document.getElementById('modalTitle').innerText = "{{ __('dashboard.categories.add_new') }}";
        removeImage();
        new bootstrap.Modal(document.getElementById('categoryModal')).show();
    }

    function editCategory(id) {
        let url = "{{ route('admin.categories.edit', ':id') }}".replace(':id', id);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data[currentLocale]) {
                    document.getElementById('catName').value = data[currentLocale].name || '';
                    document.getElementById('catSlug').value = data[currentLocale].slug || '';
                    document.getElementById('catMetaTitle').value = data[currentLocale].meta_title || '';
                    document.getElementById('catMetaDesc').value = data[currentLocale].meta_description || '';
                } else {
                    document.getElementById('catName').value = '';
                    document.getElementById('catSlug').value = '';
                    document.getElementById('catMetaTitle').value = '';
                    document.getElementById('catMetaDesc').value = '';
                }

                document.getElementById('categoryStatus').value = data.status ? 1 : 0;

                if(data.image_url) {
                    document.getElementById('imgPreview').src = data.image_url;
                    document.getElementById('imagePreviewContainer').classList.remove('d-none');
                } else {
                    removeImage();
                }

                document.getElementById('formMethod').value = 'PUT';
                let updateUrl = "{{ route('admin.categories.update', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id);
                document.getElementById('categoryForm').action = updateUrl;
                document.getElementById('modalTitle').innerText = "{{ __('dashboard.categories.edit_category') }}";
                new bootstrap.Modal(document.getElementById('categoryModal')).show();
            });
    }
</script>
@endpush
