@extends('layouts.admin.app')

@section('content')
    <main x-data="{ itemId: null, deleteRoute: '' }">
        <div class="page-header page-header-dark bg-gradient-primary-to-secondary mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title fs-1">
                                <div class="page-header-icon"><i data-feather="file"></i></div>
                                {{ $total_banner }} Total Spanduk
                            </h1>
                            <div class="page-header-subtitle">Lihat dan perbarui daftar kategori Anda di sini.
                            </div>
                        </div>
                        <div class="col-12 col-md-auto ">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('banner.create') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus me-1">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Buat Spanduk Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main page content-->
        <div class="container-xl list-item">
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

            <h4 class="mb-0 mt-5">Spanduk</h4>
            <hr class="mt-2 mb-4">

            <div class="mb-3">
                <form action="{{ route('banner.index') }}" id="orderForm" action="">
                    <div class="order-header row g-2 g-xl-4 mb-4 flex-wrap">
                        <div class="col-12">
                            <div class="input-group input-group-joined input-group-solid">
                                <input class="form-control pe-0 " type="search" placeholder="Nama Spanduk"
                                    aria-label="Search" name="search" value="{{ request()->query('search') }}" />
                                <div class="input-group-text"><i data-feather="search"></i></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (count($banners) > 0)
                @foreach ($banners as $banner)
                    <x-pages.admin.banner.banner-item :banner="$banner" />
                @endforeach
            @else
                <div>
                    <div colspan="6" class="text-center border-0">
                        <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                            style="max-width: 200px;">
                        <h5 class="text-muted">Tidak ada spanduk yang ditemukan</h5>
                    </div>
                </div>
            @endif

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
                                <p>Apakah anda yakin menghapusnya?</p>
                            </div>
                            <div class="modal-footer"><button class="btn btn-danger" type="button"
                                    data-bs-dismiss="modal">Batal</button><button class="btn btn-success"
                                    type="submit">OK</button></div>
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
    </style>
@endpush
