@extends('layouts.admin.app')

@section('content')
    <main x-data>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="file-plus"></i></div>
                                Produk
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('product.index') }}">
                                <i class="me-1" data-feather="arrow-left"></i>
                                Kembali ke Semua Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container py-5">
            <div class="d-flex row mb-5">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="position-relative">
                        <div class="{{ $product->stock > 0 ? "d-none" : "" }}">
                            <div class="isEmpity top-0 start-0 end-0 bottom-0 d-flex justify-content-center align-items-center position-absolute" style="z-index: 2">
                                <div class="rounded-circle p-5 d-flex justify-content-center align-items-center" style="background-color: rgba(0,0,0,.5); aspect-ratio: 1/1">
                                    <span class="text-white">Habis</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="product-image mb-3 image d-flex justify-content-center  bg-card rounded-3 overflow-hidden ratio ratio-1x1 align-items-center">
                            <img src="{{ asset("storage/" . $product->image) }}"
                                style="background-position: center; object-fit: contain" />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <h2 class="product-name h2 fw-bold">{{ $product->name }}</h2>
                    <div class="product-price fw-bold fs-3 mb-2 text-primary"
                        x-text="$store.globalState.formattedPrice({{ $product->price }})">Rp 0</div>
                    <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent">Kategori : {{ $product->category->name }}</li>
                            <li class="list-group-item bg-transparent">Terjual : <span
                                    x-text="$store.globalState.formatNumberShort({{ $product->total_sold ?? 0 }})">0</span>
                            </li>
                            <li class="list-group-item bg-transparent">Peringkat :
                                {{ round($product->reviews_avg_rating, 1) ?? 0 }} <img src="/icons/rate.svg" alt=""
                                    style="width: 20px; height: 20px; transform: translateY(-2px)"></li>
                            <li class="list-group-item bg-transparent">Penilaian : <span
                                    x-text="$store.globalState.formatNumberShort({{ $product->review_count ?? 0 }})">0</span>
                            </li>
                            <li class="list-group-item bg-transparent">Stok : {{ $product->stock }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div>
                <div class="product-description mb-5">
                    <div class="fw-semibold h4">Deskripsi</div>
                    <div>
                        {!! Str::markdown($product->description) !!}
                    </div>
                </div>
            </div>
            <div x-show="showAnimationAddCart">
                <div
                    class="position-fixed top-0 bg-light bottom-0 end-0 start-0 z-2 d-flex
                    justify-content-center align-items-center pe-none">
                    <div style="width: 200px; height: 200px">
                        <div id="addcart_animation"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
