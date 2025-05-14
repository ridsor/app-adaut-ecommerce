@extends('layouts.app')

@section('content')
    <main x-data>
        <div id="layoutError_content">
            <div class="container-xl px-4 py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center mt-4">
                            <img class="img-fluid p-4" src="/assets/img/illustrations/404-error.svg" alt="" />
                            <p class="lead">Oops, {{ $name ?? 'produk' }} nggak ditemukan</p>
                            <a class="text-arrow-icon small text-decoration-none d-flex align-items-center justify-content-center"
                                @click.prevent="window.history.back()">
                                <i class="fa-solid fa-arrow-left me-1"
                                    style="font-size: 14px; transform:translateY(-.5px)"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('head')
    <style>
        #layoutError_content p {
            color: #69707a !important
        }
    </style>
@endpush
