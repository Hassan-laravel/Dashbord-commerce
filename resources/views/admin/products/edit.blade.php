@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Right Column: Translatable Product Data --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-pencil-square me-2"></i> {{ __('dashboard.products.edit') }}
                            ({{ config('language.supported.' . app()->getLocale() . '.name') }})
                        </h5>
                        {{-- Alert if this specific language version is not translated yet --}}
                        @if(!$product->hasTranslation(app()->getLocale()))
                            <span class="badge bg-warning text-dark small">{{ __('dashboard.general.not_translated_yet') }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- Fetch current locale data --}}
                        @php $trans = $product->translateOrNew(app()->getLocale()); @endphp

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.name') }}</label>
                            <input type="text" name="{{ app()->getLocale() }}[name]" id="product_name"
                                   class="form-control" value="{{ $trans->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.slug') }}</label>
                            <input type="text" name="{{ app()->getLocale() }}[slug]" id="product_slug"
                                   class="form-control bg-light" value="{{ $trans->slug }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.short_description') }}</label>
                            <textarea name="{{ app()->getLocale() }}[short_description]" class="form-control" rows="2">{{ $trans->short_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.description') }}</label>
                            <textarea name="{{ app()->getLocale() }}[description]" class="form-control" rows="5">{{ $trans->description }}</textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-secondary mb-3">{{ __('dashboard.products.seo_settings') }}</h6>
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.products.meta_title') }}</label>
                            <input type="text" name="{{ app()->getLocale() }}[meta_title]" class="form-control" value="{{ $trans->meta_title }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('dashboard.products.meta_description') }}</label>
                            <textarea name="{{ app()->getLocale() }}[meta_description]" class="form-control" rows="2">{{ $trans->meta_description }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Gallery: Existing Images and New Uploads --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-images me-2"></i> {{ __('dashboard.products.gallery') }}</h6>
                    </div>
                    <div class="card-body">
                        {{-- Display existing gallery images --}}
                        <div class="row mb-4" id="current_gallery">
                            @foreach($product->images as $img)
                                <div class="col-md-3 col-6 mb-2 position-relative gallery-item-{{ $img->id }}">
                                    <img src="{{ $img->image_url }}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                            onclick="deleteGalleryImage({{ $img->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Upload New Gallery Images') }}</label>
                            <input type="file" name="images[]" class="form-control" multiple id="gallery_input" accept="image/*">
                        </div>
                        <div id="gallery_preview" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            {{-- Left Column: Static Global Data --}}
            <div class="col-lg-4">
                {{-- Category Selection --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.categories.title') }}</div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       id="cat-{{ $category->id }}"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat-{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Main Product Image --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.products.main_image') }}</div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="main_image_preview" src="{{ $product->main_image_url }}" class="img-fluid rounded border mb-2" style="max-height: 200px;">
                            <input type="file" name="main_image" class="form-control" onchange="previewMainImage(this)">
                        </div>
                    </div>
                </div>

                {{-- Financials & Inventory --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.price') }}</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ $product->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.discount_price') }}</label>
                            <input type="number" name="discount_price" step="0.01" class="form-control" value="{{ $product->discount_price }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.quantity') }}</label>
                            <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
                        </div>
                        <div class="form-check form-switch border rounded p-3 d-flex justify-content-between align-items-center">
                            <label class="form-check-label fw-bold mb-0">{{ __('dashboard.products.status') }}</label>
                            <input class="form-check-input ms-0" type="checkbox" name="status" value="1" {{ $product->status ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow">{{ __('dashboard.general.save_changes') }}</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light border">{{ __('dashboard.general.cancel') }}</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    /**
     * AJAX call to delete a gallery image from the server
     */
    function deleteGalleryImage(id) {
        if(confirm('{{ __('dashboard.general.confirm_delete') }}')) {
            fetch(`/admin/products/image/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if(response.ok) {
                    document.querySelector('.gallery-item-' + id).remove();
                }
            });
        }
    }

    /**
     * Automated Slug Generation
     */
    document.getElementById('product_name').addEventListener('input', function() {
        let text = this.value;
        text = text.toLowerCase()
                  .replace(/[^a-z0-9\u0600-\u06FF]+/g, '-') // Supports Arabic URL slugs
                  .replace(/^-+|-+$/g, '');
        document.getElementById('product_slug').value = text;
    });

    /**
     * Preview for the Main Thumbnail Image
     */
    function previewMainImage(input) {
        const preview = document.getElementById('main_image_preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Preview for newly selected gallery images
     */
    document.getElementById('gallery_input').addEventListener('change', function() {
        const previewContainer = document.getElementById('gallery_preview');
        previewContainer.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
