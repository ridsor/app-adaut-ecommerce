@extends('layouts.admin.app')

@section('content')
    <main>
        <div class="container-xl px-4 pt-4">
            <!-- Knowledge base article-->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <a class="btn btn-transparent-dark btn-icon" href="{{ route('banner.index') }}"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-arrow-left">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg></a>
                    <div class="ms-3">
                        <h2 class="my-3">Spanduk</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col col-12 col-md-6 col-lg-4">
                            <div
                                class="product-image mb-3 image d-flex justify-content-center ratio ratio-1x1 align-items-center bg-card rounded-3 overflow-hidden">
                                <div class="wrapper d-flex justify-content-center align-items-center">
                                    <img src="{{ asset("storage/" . $banner->image) }}" style="background-position: center"
                                        class="h-100 object-fit-contain" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 mt-4 mt-lg-0 col-lg-8">
                            <h4>Judul</h4>
                            <p class="lead mb-4">{{ $banner->title }}</p>
                            <h4>Deskripsi</h4>
                            <p class="lead mb-4">{{ $banner->description }}</p>
                            @if ($banner->button_text)
                                <h4>Teks Tombol</h4>
                                <p class="lead mb-4">{{ $banner->button_text }}</p>
                                <h4>Tautan Tombol</h4>
                                <p class="lead mb-4">{{ $banner->button_link }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
