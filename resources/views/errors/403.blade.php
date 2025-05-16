@extends('layouts.app')

@section('content')
    <main x-data>
        <div id="layoutError_content">
            <main>
                <div class="container-xl px-4 py-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <img class="img-fluid p-4" src="/assets/img/illustrations/403-error-forbidden.svg"
                                    alt="" />
                                <p class="lead">Klien Anda tidak memiliki izin untuk mendapatkan halaman ini dari server.
                                </p>
                                <a class="text-arrow-icon small text-decoration-none d-flex align-items-center justify-content-center"
                                    style="cursor: pointer;" @click.prevent="window.history.back()">
                                    <i class="fa-solid fa-arrow-left me-1"
                                        style="font-size: 14px; transform:translateY(-.5px)"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
