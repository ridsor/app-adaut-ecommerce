@extends('layouts.admin.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">Buat Produk</h1>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                    <br />
                                @endforeach
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" name="price" class="form-control" id="price"
                                    value="{{ old('price') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" id="stock"
                                    value="{{ old('stock') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea required name="description" id="description" cols="30" rows="3" class="form-control"
                                    value="{{ old('description') }}"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" x-data="imagePreview('{{ old('image') }}')">
                                <label for="image" class="form-label">Gambar</label>
                                <input type="file" name="image" id="image" class="form-control" required
                                    accept="image/*" @change="previewFile($event)">
                                <div class="preview-container" :class="{ 'hidden': !imageUrl }">
                                    <img id="preview-image" :src="imageUrl" alt="Preview gambar">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-grid">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        #preview-image {
            max-width: 300px;
            max-height: 300px;
            margin: 10px auto;
            display: block;
        }

        .preview-container {
            margin-top: 15px;
        }

        .hidden {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script async>
        function imagePreview(defaultImageUrl = '') {
            return {
                imageUrl: defaultImageUrl,
                defaultImageUrl: defaultImageUrl,

                previewFile(event) {
                    const file = event.target.files[0];
                    if (!file || !file.type.includes('image')) {
                        this.imageUrl = '';
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
                },
                resetToDefault() {
                    this.imageUrl = this.defaultImageUrl;
                    document.getElementById('image-input').value = '';
                }
            }
        }
    </script>
@endpush
