@extends('layouts.app')

@section('content')
    <main>
        <section>
            <div class="container mt-5">
                <form action="{{ route('contact-us.store') }}" method="POST">
                    @csrf
                    <div class="mb-0 fs-6">Bantuan & Dukungan</div>
                    <hr class="mt-2 mb-4">
                    @if (Session::has('status'))
                        <div class="mb-4 alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('status') }}
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="mb-4">
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                                type="email" value="{{ old('email') }}" placeholder="Email Anda" />
                            <div class="invalid-feedback">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                type="name" value="{{ old('name') }}" placeholder="Nama Anda" />
                            <div class="invalid-feedback">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="fa-solid fa-circle-question"></i>
                            </span>
                            <input class="form-control @error('subject') is-invalid @enderror" name="subject" id="subject"
                                type="subject" value="{{ old('subject') }}" placeholder="Subjek" />
                            <div class="invalid-feedback">
                                @error('subject')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <textarea id="description" class="lh-base form-control @error('description') is-invalid @enderror" type="text"
                            name="description">{{ old('description') }}</textarea>
                        <div class="invalid-feedback">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="fw-500 btn btn-primary px-5">Kirim</button>

                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection

@push('head')
    <link href="https://unpkg.com/easymde/dist/easymde.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/easymde/dist/easymde.min.js" crossorigin="anonymous"></script>
    <script>
        var easyMDE = new EasyMDE({
            element: document.getElementById('description'),
            toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link',
                'image', '|', 'preview', 'guide'
            ],
            minHeight: "100px",
        });
    </script>
@endpush
