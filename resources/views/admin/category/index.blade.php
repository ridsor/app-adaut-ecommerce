@extends('layouts.admin.app')

@section('content')
    <main x-data="{ itemId: null, deleteRoute: '' }">
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="file"></i></div>
                                {{ $total_categories }} Total kategori
                            </h1>
                            <div class="page-header-subtitle">Lihat dan perbarui daftar kategory Anda di sini.
                            </div>
                        </div>
                        <div class="col-12 col-md-auto ">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('category.create') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus me-1">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Buat Kategori Baru
                            </a>
                        </div>
                    </div>
                    <div class="page-header-search mt-4">
                        <form method="GET">
                            <div class="input-group input-group-joined">
                                <input class="form-control" type="text" name="search" placeholder="Cari..."
                                    aria-label="Cari" autofocus="">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 list-item">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h4 class="mb-0 mt-5">Kategori</h4>
            <hr class="mt-2 mb-4">

            @if (count($categories) > 0)
                @foreach ($categories as $category)
                    <x-pages.admin.category.category-item :category="$category" />
                @endforeach
            @else
                <div>
                    <div class="display-6">
                        Data tidak ditemukan
                    </div>
                </div>
            @endif
        </div>
        <!-- Modal -->
        <form :action="deleteRoute" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Hapus</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Produk yang memiliki kategori ini akan terhapus.
                                <br />Apakah anda yakin menghapusnya?
                            </p>
                        </div>
                        <div class="modal-footer"><button class="btn btn-danger" type="button"
                                data-bs-dismiss="modal">Batal</button><button class="btn btn-success"
                                type="submit">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('head')
    <style>
        .list-item .item .dropdown button::after {
            display: none !important
        }
    </style>
@endpush
