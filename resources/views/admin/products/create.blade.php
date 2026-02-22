@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Right Column: Translatable Product Data --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-plus-circle me-2"></i> {{ __('dashboard.products.add_new') }}
                            <span class="badge bg-light text-primary border ms-2">{{ config('language.supported.' . app()->getLocale() . '.name') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Language indicator included next to each translatable field label --}}

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                {{ __('dashboard.products.name') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <input type="text" name="{{ app()->getLocale() }}[name]" id="product_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                {{ __('dashboard.products.slug') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <input type="text" name="{{ app()->getLocale() }}[slug]" id="product_slug" class="form-control bg-light" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                {{ __('dashboard.products.short_description') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <textarea name="{{ app()->getLocale() }}[short_description]" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                {{ __('dashboard.products.description') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <textarea name="{{ app()->getLocale() }}[description]" class="form-control" rows="5"></textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-secondary mb-3">{{ __('dashboard.products.seo_settings') }}</h6>

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('dashboard.products.meta_title') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <input type="text" name="{{ app()->getLocale() }}[meta_title]" id="meta_title" class="form-control">
                        </div>

                        <div class="mb-0">
                            <label class="form-label">
                                {{ __('dashboard.products.meta_description') }}
                                <small class="text-muted fw-normal">({{ config('language.supported.' . app()->getLocale() . '.name') }})</small>
                            </label>
                            <textarea name="{{ app()->getLocale() }}[meta_description]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Product Gallery (Multiple Images) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-images me-2"></i> {{ __('dashboard.products.gallery') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('dashboard.products.gallery') }}</label>
                            <input type="file" name="images[]" class="form-control" multiple id="gallery_input" accept="image/*">
                        </div>
                        <div id="gallery_preview" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            {{-- Left Column: Static/Non-translatable Data --}}
            <div class="col-lg-4">
                {{-- Multi-Category Selection --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.categories.title') }}</div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat-{{ $category->id }}">
                                <label class="form-check-label" for="cat-{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Main Thumbnail Image --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 fw-bold">{{ __('dashboard.products.main_image') }}</div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="main_image_preview" src="" class="img-fluid rounded border d-none mb-2" style="max-height: 200px;">
                            <input type="file" name="main_image" class="form-control" required onchange="previewMainImage(this)">
                        </div>
                    </div>
                </div>

                {{-- Pricing, Inventory, and Status --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.price') }}</label>
                            <div class="input-group">
                                <input type="number" name="price" step="0.01" class="form-control" required>
                                <span class="input-group-text">$</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.quantity') }}</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.products.sku') }}</label>
                            <input type="text" name="sku" class="form-control">
                        </div>
                        <div class="form-check form-switch border rounded p-3 d-flex justify-content-between align-items-center">
                            <label class="form-check-label fw-bold mb-0">{{ __('dashboard.products.status') }}</label>
                            <input class="form-check-input ms-0" type="checkbox" name="status" value="1" checked>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="bi bi-save me-2"></i> {{ __('dashboard.general.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    /**
     * 1. Automatically generate Slug
     * 2. Automatically sync Meta Title with Product Name for better UX
     */
    document.getElementById('product_name').addEventListener('input', function() {
        let text = this.value;

        // Slug Generation Logic
        let slug = text.toLowerCase()
                  .replace(/[^a-z0-9\u0600-\u06FF]+/g, '-') // Support Arabic characters in URL
                  .replace(/^-+|-+$/g, '');
        document.getElementById('product_slug').value = slug;

        // Meta Title Syncing
        document.getElementById('meta_title').value = text;
    });

    /**
     * Main Thumbnail Preview
     */
    function previewMainImage(input) {
        const preview = document.getElementById('main_image_preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Gallery Multi-Image Preview
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
