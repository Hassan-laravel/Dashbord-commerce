@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-pencil-square me-2"></i> {{ __('dashboard.pages.edit') }}
                            ({{ config('language.supported.' . app()->getLocale() . '.name') }})
                        </h5>
                        {{-- Show warning badge if the translation for the current locale doesn't exist yet --}}
                        @if(!$page->hasTranslation(app()->getLocale()))
                            <span class="badge bg-warning text-dark small">{{ __('dashboard.general.not_translated_yet') }}</span>
                        @endif
                    </div>
                    <div class="card-body">

                        {{-- Retrieve current translation or create a new instance --}}
                        @php $trans = $page->translateOrNew(app()->getLocale()); @endphp

                        {{-- Page Title Field --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.pages.title_label') }}</label>
                            <input type="text" name="{{ app()->getLocale() }}[title]" value="{{ $trans->title }}" id="page_title" class="form-control" required>
                        </div>

                        {{-- Slug (URL Identifier) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.pages.slug') }}</label>
                            {{-- Note: Field is readonly to prevent accidental manual changes; it updates via JS --}}
                            <input type="text" name="slug" value="{{ $page->slug }}" id="page_slug" class="form-control bg-light" readonly>
                        </div>

                        {{-- Main Content Editor (CKEditor) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('dashboard.pages.content') }}</label>
                            <textarea name="{{ app()->getLocale() }}[content]" id="editor" class="form-control">{{ $trans->content }}</textarea>
                        </div>

                        <hr>

                        {{-- SEO Metadata Section --}}
                        <div class="p-3 bg-light rounded mt-4">
                            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-search me-1"></i> {{ __('dashboard.products.seo_settings') }}</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('dashboard.products.meta_title') }}</label>
                                <input type="text" name="{{ app()->getLocale() }}[meta_title]" value="{{ $trans->meta_title }}" id="meta_title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('dashboard.products.meta_description') }}</label>
                                <textarea name="{{ app()->getLocale() }}[meta_description]" id="meta_description" class="form-control" rows="2">{{ $trans->meta_description }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-bold">{{ __('dashboard.pages.keywords') }}</label>
                                <input type="text" name="{{ app()->getLocale() }}[meta_keywords]" value="{{ $trans->meta_keywords }}" id="meta_keywords" class="form-control">
                            </div>
                        </div>

                        {{-- Page Status & Save Button --}}
                        <div class="mt-4">
                            <div class="form-check form-switch p-3 border rounded mb-3 bg-white">
                                <label class="form-check-label fw-bold" for="statusSwitch">{{ __('dashboard.products.status') }}</label>
                                <input class="form-check-input float-end" type="checkbox" id="statusSwitch" name="status" value="1" {{ $page->status ? 'checked' : '' }}>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                                <i class="bi bi-save me-2"></i> {{ __('dashboard.general.save_changes') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Load CKEditor 5 Script --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let myEditor;

    // Initialize Editor and store instance in variable
    ClassicEditor
        .create(document.querySelector('#editor'), {
            language: '{{ app()->getLocale() }}'
        })
        .then(editor => {
            myEditor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Handle Title input to auto-update Slug and SEO fields
    document.getElementById('page_title').addEventListener('input', function() {
        let title = this.value;

        // 1. Update Slug (Supports Arabic and English characters)
        let slug = title.trim()
            .replace(/\s+/g, '-')                    // Replace spaces with dashes
            .replace(/[^\w\u0600-\u06FF\-]+/g, '')  // Allow Alphanumeric, Arabic chars, and dashes
            .replace(/\-\-+/g, '-')                  // Prevent duplicate dashes
            .replace(/^-+/, '')                      // Trim leading dash
            .replace(/-+$/, '');                     // Trim trailing dash

        document.getElementById('page_slug').value = slug;

        // 2. Sync SEO Meta fields
        document.getElementById('meta_title').value = title;
        document.getElementById('meta_description').value = title;
        document.getElementById('meta_keywords').value = title;

        // 3. Update CKEditor content dynamically
        if(myEditor) {
            myEditor.setData(`<p>${title}</p>`);
        }
    });
</script>
<style>
    /* Ensure a consistent height for the editor area */
    .ck-editor__editable_inline { min-height: 300px; }
</style>
@endpush
