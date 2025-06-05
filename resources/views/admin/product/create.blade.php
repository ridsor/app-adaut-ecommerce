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
                                Buat Produk
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('admin.product.index') }}">
                                <i class="me-1" data-feather="arrow-left"></i>
                                Kembali ke Semua Produk
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
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gx-4">
                    <div class="col">
                        <div class="card mb-4" x-data="imagePreview('{{ old('image') }}')">
                            <div class="card-header">Gambar</div>
                            <div class="card-body">
                                <label class="file-label d-block">
                                    <span x-text="fileName"
                                        class="fw-500 btn btn-primary form-control w-100 @error('image') is-invalid @enderror">Tidak
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
                            <div class="card-header">Kategori</div>
                            <div class="card-body">
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="" selected>Pilih kategori produk anda...</option>
                                    @foreach ($categories as $category)
                                        @if ($category->id === old('category_id'))
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Nama</div>
                            <div class="card-body"><input class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" type="text" value="{{ old('name') }}"
                                    placeholder="Masukkan nama produk Anda..." />
                                <div class="invalid-feedback">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Harga</div>
                            <div class="card-body">
                                <div class="input-group input-group-joined">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                    <input class="form-control @error('price') is-invalid @enderror" name="price"
                                        id="price" type="number" value="{{ old('price') }}"
                                        placeholder="Masukkan harga produk Anda..." />
                                        <div class="invalid-feedback">
                                            @error('price')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Stok</div>
                            <div class="card-body">
                                <input class="form-control @error('stock') is-invalid @enderror" name="stock"
                                    id="stock" type="number" value="{{ old('stock') }}"
                                    placeholder="Masukkan stok produk Anda..." />

                                <div class="invalid-feedback">
                                    @error('stock')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">Berat</div>
                            <div class="card-body">
                                <div class="input-group input-group-joined">
                                    <span class="input-group-text">
                                        Gram
                                    </span>
                                    <input class="form-control @error('weight') is-invalid @enderror" name="weight"
                                        id="weight" type="number" value="{{ old('weight') }}"
                                        placeholder="Masukkan harga produk Anda..." />
                                        <div class="invalid-feedback">
                                            @error('weight')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-header-actions mb-4">
                            <div class="card-header">
                                Deskripsi Produk
                            </div>
                            <div class="card-body">
                                <textarea id="description" class="lh-base form-control @error('description') is-invalid @enderror" type="text"
                                    name="description" placeholder="Masukkan deskripsi spanduk Anda...">{{ old('description') }}</textarea>
                                <div class="invalid-feedback">
                                    @error('description')
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
    <link href="https://unpkg.com/easymde/dist/easymde.min.css" rel="stylesheet" />
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
    <script src="https://unpkg.com/easymde/dist/easymde.min.js" crossorigin="anonymous"></script>
    <script>
        var easyMDE = new EasyMDE({
            element: document.getElementById('description'),
            toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link',
                'image', '|', 'preview', 'guide'
            ],
            minHeight: "100px",
        });

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
