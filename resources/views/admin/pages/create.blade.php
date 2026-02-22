@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-file-earmark-plus me-2"></i> {{ __('dashboard.pages.add_new') }}
                                ({{ config('language.supported.' . app()->getLocale() . '.name') }})
                            </h5>
                        </div>
                        <div class="card-body">

                            {{-- Page Title --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('dashboard.pages.title_label') }}</label>
                                <input type="text" name="{{ app()->getLocale() }}[title]" id="page_title"
                                    class="form-control" required>
                            </div>

                            {{-- Slug (URL Identifier) --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('dashboard.pages.slug') }}</label>
                                <input type="text" name="slug" id="page_slug" class="form-control bg-light" readonly>
                            </div>

                            {{-- Main Content (CKEditor) --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('dashboard.pages.content') }}</label>
                                <textarea name="{{ app()->getLocale() }}[content]" id="editor" class="form-control"></textarea>
                            </div>

                            <hr>

                            {{-- SEO Settings Section --}}
                            <div class="p-3 bg-light rounded mt-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="bi bi-search me-1"></i> {{ __('dashboard.products.seo_settings') }}
                                </h6>
                                <div class="mb-3">
                                    <label
                                        class="form-label small fw-bold">{{ __('dashboard.products.meta_title') }}</label>
                                    <input type="text" name="{{ app()->getLocale() }}[meta_title]" id="meta_title"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label small fw-bold">{{ __('dashboard.products.meta_description') }}</label>
                                    <textarea name="{{ app()->getLocale() }}[meta_description]" id="meta_description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold">{{ __('dashboard.pages.keywords') }}</label>
                                    <input type="text" name="{{ app()->getLocale() }}[meta_keywords]" id="meta_keywords"
                                        class="form-control">
                                </div>
                            </div>

                            {{-- Status & Action Button --}}
                            <div class="mt-4">
                                <div class="form-check form-switch p-3 border rounded mb-3 bg-white">
                                    <label class="form-check-label fw-bold"
                                        for="statusSwitch">{{ __('dashboard.products.status') }}</label>
                                    <input class="form-check-input float-end" type="checkbox" id="statusSwitch"
                                        name="status" value="1" checked>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                                    <i class="bi bi-save me-2"></i> {{ __('dashboard.general.save') }}
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
    {{-- Load CKEditor 5 Classic --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let myEditor;

        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: '{{ app()->getLocale() }}'
            })
            .then(editor => {
                myEditor = editor; // Save instance for later manipulation
            })
            .catch(error => {
                console.error(error);
            });

        // Listen to Page Title input for auto-generating fields
        document.getElementById('page_title').addEventListener('input', function() {
            let title = this.value;

            // 1. Generate Slug (supports Arabic & Latin characters)
            let slug = title.trim()
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .replace(/[^\w\u0600-\u06FF\-]+/g, '') // Remove special chars but keep Arabic
                .replace(/\-\-+/g, '-') // Prevent multiple consecutive dashes
                .replace(/^-+/, '') // Trim dashes from start
                .replace(/-+$/, ''); // Trim dashes from end

            document.getElementById('page_slug').value = slug;

            // 2. Sync Title with Meta Title
            document.getElementById('meta_title').value = title;

            // 3. Sync Title with Meta Description (Initial draft)
            document.getElementById('meta_description').value = title;

            // 4. Sync Title with Keywords
            document.getElementById('meta_keywords').value = title;

            // 5. Inject Title into CKEditor content automatically
            if (myEditor) {
                myEditor.setData(`<p>${title}</p>`);
            }
        });
    </script>
    <style>
        /* Set minimum height for the editor box */
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
@endpush
