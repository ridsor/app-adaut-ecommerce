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
                                {{ $total_items }} Total Produk
                            </h1>
                            <div class="page-header-subtitle">Lihat dan perbarui daftar produk Anda di sini.
                            </div>
                        </div>
                        <div class="col-12 col-md-auto ">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('product.create') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus me-1">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Buat Produk Baru
                            </a>
                        </div>
                    </div>
                    <div class="page-header-search mt-4">
                        <form>
                            <div class="row g-3 flex-column flex-md-row">
                                <div class="col flex-grow-1">
                                    <div class="input-group input-group-joined">
                                        <input class="form-control" type="text" name="search"
                                            placeholder="Cari produk berdasarkan nama..." aria-label="Cari" autofocus="">
                                        <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="feather feather-search">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg></span>
                                    </div>
                                </div>
                                <div class="col-auto d-flex">
                                    <select class="form-control" name="sort" aria-label="sort" @change="sorftBy($event)">
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
                                <div class="col-auto d-flex flex-column flex-md-row">
                                    <button class="btn btn-primary mb-0" type="button" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                                        <i class="fas fa-sliders-h me-1"></i> Filter
                                    </button>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSidebar"
                                        aria-labelledby="offcanvasSidebarLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h6 class="offcanvas-title" id="offcanvasSidebarLabel">Filter</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                                data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body flex-column p-3 p-xl-0">
                                            <div class="accordion accordion-alt" id="accordionSearch">
                                                <!-- Availability -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button fs-6 fw-semibold" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                            aria-expanded="true" aria-controls="collapseOne">
                                                            Ketersediaan
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                                        data-bs-parent="#accordionSearch" style="">
                                                        <div class="accordion-body">

                                                            <!-- Availability group -->
                                                            <div class="form-check">
                                                                <input class="form-check-input focus-shadow-none"
                                                                    type="radio" checked name="availability"
                                                                    id="availability1" value="all">
                                                                <label class="form-check-label" for="availability1">
                                                                    Semua
                                                                </label>
                                                            </div>
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input focus-shadow-none"
                                                                    type="radio" value="stock"
                                                                    {{ request()->input('availability') === 'stock' ? 'checked' : '' }}
                                                                    name="availability" id="availability3">
                                                                <label class="form-check-label" for="availability3">
                                                                    Stok Tersedia
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Categories -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button fs-6 fw-semibold collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTwo" aria-expanded="false"
                                                            aria-controls="collapseTwo">
                                                            Kategori
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionSearch" style="">
                                                        <div class="accordion-body">
                                                            @foreach ($categories as $category)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="categories[]"
                                                                        {{ in_array($category->slug, request()->input('categories', [])) ? 'checked' : '' }}
                                                                        type="checkbox" value="{{ $category->slug }}"
                                                                        id='category{{ $category->id }}' />
                                                                    <label class="form-check-label heading-color"
                                                                        for='category{{ $category->id }}'>{{ $category->name }}</label>
                                                                    <span
                                                                        class="small float-end">({{ $category->products_count }})</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Price -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button fs-6 fw-semibold collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseThree" aria-expanded="false"
                                                            aria-controls="collapseThree">
                                                            Harga
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThree" class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionSearch">
                                                        <div class="accordion-body">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Harga Minimun" name="min_price"
                                                                    value="{{ request()->query('min_price') }}"
                                                                    aria-label="Username"
                                                                    aria-describedby="basic-addon1" />
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Harga Maksimum" name="max_price"
                                                                    value="{{ request()->query('max_price') }}"
                                                                    aria-label="Username"
                                                                    aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Rating star -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button fs-6 fw-semibold collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseFour" aria-expanded="false"
                                                            aria-controls="collapseFour">
                                                            Bintang Peringkat
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFour" class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionSearch">
                                                        <div class="accordion-body">
                                                            <ul class="list-inline mb-0 g-3">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <!-- Item -->
                                                                    <li class="list-inline-item mb-0">
                                                                        <input type="checkbox"
                                                                            {{ in_array($i, request()->input('rating', [])) ? 'checked' : '' }}
                                                                            name="rating[]" value='{{ $i }}'
                                                                            class="btn-check"
                                                                            id="btn-check-{{ $i }}">
                                                                        <label
                                                                            class="btn btn-sm btn-light btn-primary-soft-check"
                                                                            for="btn-check-{{ $i }}">{{ $i }}<i
                                                                                class="bi bi-star-fill"></i></label>
                                                                    </li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between p-2 mt-xl-3 align-items-center">
                                                <a class="btn btn-link text-primary-hover p-0 mb-0 text-decoration-none"
                                                    href="{{ route('product.index') }}?{{ http_build_query(['value' => request()->query('value')]) }}">Hapus
                                                    Semua</a>
                                                <button class="btn btn-primary mb-0" type="submit">Terapkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            <div class="d-flex mt-5 align-items-center justify-content-between gap-2">
                <h4 class="mb-0">Produk</h4>
            </div>
            <hr class="mt-2 mb-4">

            @if (count($products) > 0)
                @foreach ($products as $product)
                    <div class="item card card-icon lift lift-sm mb-3 position-static overflow-hidden"
                        style="cursor: pointer" role="link" tabindex="0" aria-label="product item"
                        @click="window.location.href='{{ route('product.show', ['produk' => $product->slug]) }}'"
                        @keydown.enter="window.location.href='{{ route('product.show', ['produk' => $product->slug]) }}'"
                        @keydown.space.prevent="window.location.href='{{ route('product.show', ['produk' => $product->slug]) }}'">
                        <div class="row g-2 flex-column flex-md-row">
                            <div class="col-auto card-icon-aside p-0" style="background: #f9f9f9">
                                <div class="ratio ratio-1x1  overflow-hidden" style="width: 112px">
                                    <div class="wrapper align-items-center d-flex justify-content-center">
                                        <img src="{{ $product->image }}" style="background-position: center"
                                            class="h-100 object-fit-contain" />
                                    </div>
                                </div>
                            </div>

                            <div class="col w-100 d-flex align-items-center">
                                <div class="row p-2 p-md-3 flex-column flex-md-row flex-grow-1 g-1">
                                    <div class="col">
                                        <div class="text-dark fw-semibold">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-primary fw-semibold">
                                            <span x-text="$store.globalState.formattedPrice({{ $product->price }})">Rp
                                                0</span>
                                        </div>
                                    </div>
                                    <div class="col text-dark fw-semibold d-flex align-items-center gap-1">
                                        <div style="width: 40px">
                                            <div class="d-flex justify-content-center ratio ratio-1x1 align-items-center">
                                                <img src="{{ $product->category->icon }}"
                                                    style="background-position: center; object-fit: contain" />
                                            </div>
                                        </div>
                                        <span>
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-12 col-md-auto d-flex align-items-center justify-content-end justify-content-md-center gap-1 flex-md-column p-2">
                                <a href="{{ route('product.edit', ['produk' => $product->slug]) }}"
                                    class="btn btn-warning btn-icon" @click.stop>
                                    <i data-feather="edit"></i>
                                </a>
                                <button class="btn btn-danger btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal"
                                    @click.stop="deleteRoute = '{{ route('product.destroy', ['produk' => $product->slug]) }}'"
                                    type="button">
                                    <i data-feather="trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div>
                    <div class="display-6">
                        Data tidak ditemukan
                    </div>
                </div>
            @endif

            <div class="mt-4 pagination d-flex">
                {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>

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
                            <p>Apakah anda yakin menghapusnya?</p>
                        </div>
                        <div class="modal-footer"><button class="btn btn-danger" type="button"
                                data-bs-dismiss="modal">Batal</button><button class="btn btn-success"
                                type="submit">OK</button></div>
                    </div>
                </div>
            </div>
        </form>
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
    <script>
        function sorftBy(event) {
            const form = event.target.form;
            const params = new URLSearchParams(new FormData(form));

            window.location.href = `${form.action}?${params.toString()}`;
        }
    </script>
@endpush
