@extends('layouts.admin.app')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="file-plus"></i></div>
                                Edit Spanduk
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('banner.index') }}">
                                <i class="me-1" data-feather="arrow-left"></i>
                                Kembali ke Semua Spanduk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('banner.update', ['spanduk' => $banner->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row gx-4">
                    <div class="col">
                        <div class="card mb-4" x-data="imagePreview('{{ asset("storage/" . $banner->image) }}')">
                            <div class="card-header">Gambar</div>
                            <div class="card-body">
                                <label class="file-label d-block">
                                    <span x-text="fileName"
                                        class="fw-500 btn btn-primary form-control w-100 @error('image') is-invalid @enderror"">Tidak
                                        ada
                                        file
                                        dipilih</span>
                                    <input type="file" name="image" hidden id="image"
                                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                        @change="previewFile($event)"
                                        class="form-control file-input @error('image') is-invalid @enderror"
                                        placeholder="Masukkan gambar spanduk Anda..." />
                                    <div class="invalid-feedback">
                                        @error('image')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </label>
                                <div class="preview-container" :class="{ 'd-flex': imageUrl }">
                                    <img id="preview-image" :src="imageUrl" alt="Preview gambar">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Judul</div>
                            <div class="card-body"><input class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" type="text" value="{{ old('title', $banner->title) }}"
                                    placeholder="Masukkan judul spanduk Anda..." />
                                <div class="invalid-feedback">
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card card-header-actions mb-4">
                            <div class="card-header">
                                Deskripsi Spanduk
                            </div>
                            <div class="card-body">
                                <textarea class="lh-base form-control @error('description') is-invalid @enderror" type="text" name="description"
                                    placeholder="Masukkan deskripsi spanduk Anda..." rows="4">{{ old('description', $banner->description) }}</textarea>
                                <div class="invalid-feedback">
                                    @error('description')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 card-header-actions">
                            <div class="card-header">Tombol Teks
                                <i class="text-muted" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="left"
                                    title="Tidak wajib"></i>
                            </div>
                            <div class="card-body"><input class="form-control @error('button_text') is-invalid @enderror"
                                    name="button_text" id="button_text"
                                    value="{{ old('button_text', $banner->button_text) }}" type="text"
                                    placeholder="Masukkan tombol teks spanduk Anda..." />
                                <div class="invalid-feedback">
                                    @error('button_text')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 card-header-actions">
                            <div class="card-header">Tautan Tombol <i class="text-muted" data-feather="info"
                                    data-bs-toggle="tooltip" data-bs-placement="left" title="Tidak wajib"></i></div>
                            <div class="card-body"><input class="form-control @error('button_link') is-invalid @enderror"
                                    name="button_link" id="button_link"
                                    value="{{ old('button_link', $banner->button_link) }}" type="text"
                                    placeholder="Masukkan tautan tombol spanduk Anda..." />
                                <div class="invalid-feedback">
                                    @error('button_link')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card card-header-actions">
                            <div class="card-body">
                                <div class="d-grid"><button class="fw-500 btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('head')
    <style>
        .list-item .item .dropdown button::after {
            display: none !important
        }

        #preview-image {
            max-width: 200px;
            max-height: 200px;
            display: block;
        }

        .preview-container {
            display: none;
            margin-top: 15px;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function imagePreview(defaultImageUrl = '') {
            return {
                imageUrl: defaultImageUrl,
                defaultImageUrl: defaultImageUrl,
                fileName: 'Tidak ada file dipilih',

                previewFile(event) {
                    const file = event.target.files[0];
                    if (!file || !file.type.includes('image')) {
                        this.imageUrl = '';
                        this.fileName = 'Tidak ada file dipilih';
                        return;
                    }

                    // Buat FileReader untuk membaca file
                    const reader = new FileReader();

                    // Setup handler untuk event onload
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };

                    // Baca file sebagai data URL
                    reader.readAsDataURL(file);

                    this.fileName = file.name || 'Tidak ada file dipilih'
                },
                resetToDefault() {
                    this.imageUrl = this.defaultImageUrl;
                    document.getElementById('image-input').value = '';
                }
            }
        }
    </script>
@endpush
