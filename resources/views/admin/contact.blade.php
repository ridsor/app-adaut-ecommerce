@extends('layouts.admin.app')

@section('content')
    <main x-data='data'>
        <div class="page-header page-header-dark bg-gradient-primary-to-secondary mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title fs-1">
                                <div class="page-header-icon"><i data-feather="file"></i></div>
                                {{ $total_items }} Total Kontak
                            </h1>
                            <div class="page-header-subtitle">Lihat dan perbarui daftar kontak Anda di sini.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xl list">
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
            <div class="d-flex mt-5 align-items-center justify-content-between gap-2">
                <h4 class="mb-0">Kontak</h4>
            </div>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <form action="{{ route('admin.contact.index') }}" id="orderForm" action="">
                    <div class="order-header row g-2 g-xl-4 mb-4 flex-wrap">
                        <div class="col-12 col-lg-6">
                            <div class="input-group input-group-joined input-group-solid">
                                <input class="form-control pe-0 " type="search" placeholder="Nama / Email / Subyek"
                                    aria-label="Search" name="search" value="{{ request()->query('search') }}" />
                                <div class="input-group-text"><i data-feather="search"></i></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 ms-auto">
                            <select class="form-select py-3" name="sort" aria-label="sort" @change="sorftBy($event)">
                                <option selected value="">Urutkan</option>
                                @foreach ($sort as $x)
                                    @if ($x['value'] === request()->input('sort'))
                                        <option value="{{ $x['value'] }}" selected>{{ $x['text'] }}</option>
                                    @else
                                        <option value="{{ $x['value'] }}">{{ $x['text'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 ms-auto">
                            <select class="form-select py-3" name="status" aria-label="status"
                                @change="changeStatus($event)">
                                <option selected value="">Status</option>
                                @foreach ($status as $x)
                                    @if ($x['value'] === request()->input('status'))
                                        <option value="{{ $x['value'] }}" selected>{{ $x['text'] }}</option>
                                    @else
                                        <option value="{{ $x['value'] }}">{{ $x['text'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            
            @if (count($contacts) > 0)
                @foreach ($contacts as $contact)
                    <x-pages.admin.contact.contact-item :contact="$contact" />
                @endforeach
            @else
                <div>
                    <div class="text-center border-0">
                        <img src="/assets/img/illustrations/404-error.svg" alt="No Orders" class="img-fluid mb-3"
                            style="max-width: 200px;">
                        <h5 class="text-muted">Tidak ada kontak yang ditemukan</h5>
                    </div>
                </div>
            @endif

            <div class="mt-4 pagination d-flex">
                {!! $contacts->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>

        <div id="loading" x-ref="loading"
            style="
            position: fixed; 
            top: 0; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background-color: rgba(0,0,0,.5); 
            z-index: 2; 
            display: none; 
            justify-content: center; 
            align-items: center;">
            <div style="width: 200px; height: 200px">
                <div id="loadingAnimation"></div>
            </div>
        </div>
    </main>
@endsection

@push('head')
    <style>
        .pagination>nav {
            flex: 1 !important;
        }

        .pagination nav>div:first-of-type {
            display: none !important
        }

        .pagination nav>div:last-of-type {
            display: flex !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            column-gap: .5rem
        }

        .pagination nav>div:last-of-type ul {
            justify-content: center !important;
            flex-wrap: wrap !important;
            row-gap: .5rem
        }

        @media (min-width: 992px) {
            .pagination nav>div:last-of-type {
                justify-content: space-between !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'></script>
    <script>
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('loadingAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/assets/animations/loading.json'
        })

        function sorftBy(event) {
            const form = event.target.form;
            const params = new URLSearchParams(new FormData(form));

            window.location.href = `${form.action}?${params.toString()}`;
        }

        function changeStatus(event) {
            const form = event.target.form;
            const params = new URLSearchParams(new FormData(form));

            window.location.href = `${form.action}?${params.toString()}`;
        }

        document.addEventListener('alpine:init', () => {
            window.Alpine.data('data', () => ({
                async handleContactStatus(id, status) {
                    this.$refs.loading.style.display = 'flex'

                    const payload = {
                        status: status,
                        items: [{
                            id: id
                        }]
                    };

                    try {
                        await axios.patch(
                            '{{ route('api.admin.contact.update') }}',
                            payload, {
                                headers: {
                                    Authorization: `Bearer {{ Session::get('token') }}`,
                                    Accept: 'application/json'
                                }
                            }
                        );

                        this.$refs.loading.style.display = 'none';

                        location.reload();
                    } catch (error) {
                        this.$refs.loading.style.display = 'none';

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memperbarui kontak',
                            text: error.response?.data?.message ||
                                'Terjadi kesalahan',
                        });
                    }

                },
            }));
        });
    </script>
@endpush
