@extends("layouts.app")

@section("content")
  <form action="{{ route("search") }}" id="searchForm" action="" />
  <main x-data>
    <div class="container py-5">
      <h1 class="h2 fw-bold mb-5">Pencarian</h1>
      <div class="row">
        <div class="right col-3">
          <div class="offcanvas-xl offcanvas-end" tabindex="-1" id="offcanvasSidebar"
            aria-labelledby="offcanvasSidebarLabel">
            <div class="offcanvas-header bg-light">
              <h6 class="offcanvas-title" id="offcanvasSidebarLabel">Filter</h6>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSidebar"
                aria-label="Close"></button>
            </div>
            <div class="offcanvas-body flex-column p-3 p-xl-0">
              <div class="accordion accordion-alt" id="accordionSearch">
                <!-- Availability -->
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button fs-6 fw-semibold" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Ketersediaan
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionSearch"
                    style="">
                    <div class="accordion-body">

                      <!-- Availability group -->
                      <div class="form-check">
                        <input class="form-check-input focus-shadow-none" type="radio" checked name="availability"
                          id="availability1" value="all">
                        <label class="form-check-label" for="availability1">
                          Semua
                        </label>
                      </div>
                      <div class="form-check mt-2">
                        <input class="form-check-input focus-shadow-none" type="radio" value="stock"
                          {{ request()->input("availability") === "stock" ? "checked" : "" }} name="availability"
                          id="availability3">
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
                    <button class="accordion-button fs-6 fw-semibold collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Kategori
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionSearch"
                    style="">
                    <div class="accordion-body">
                      @foreach ($categories as $category)
                        <div class="form-check">
                          <input class="form-check-input" name="categories[]"
                            {{ in_array($category->slug, request()->input("categories", [])) ? "checked" : "" }}
                            type="checkbox" value="{{ $category->slug }}" id='category{{ $category->id }}' />
                          <label class="form-check-label heading-color"
                            for='category{{ $category->id }}'>{{ $category->name }}</label>
                          <span class="small float-end">({{ $category->products_count }})</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>

                <!-- Price -->
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button fs-6 fw-semibold collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Harga
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionSearch">
                    <div class="accordion-body">
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" placeholder="Harga Minimun" name="min_price"
                          value="{{ request()->query("min_price") }}" aria-label="Username"
                          aria-describedby="basic-addon1" />
                      </div>
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" placeholder="Harga Maksimum" name="max_price"
                          value="{{ request()->query("max_price") }}" aria-label="Username"
                          aria-describedby="basic-addon1" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Rating star -->
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button fs-6 fw-semibold collapsed" type="button"
                      data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                      aria-controls="collapseFour">
                      Bintang Peringkat
                    </button>
                  </h2>
                  <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionSearch">
                    <div class="accordion-body">
                      <ul class="list-inline mb-0 g-3">
                        @for ($i = 1; $i <= 5; $i++)
                          <!-- Item -->
                          <li class="list-inline-item mb-0">
                            <input type="checkbox" {{ in_array($i, request()->input("rating", [])) ? "checked" : "" }}
                              name="rating[]" value='{{ $i }}' class="btn-check"
                              id="btn-check-{{ $i }}">
                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                              for="btn-check-{{ $i }}">{{ $i }}<i
                                class="bi bi-star-fill"></i></label>
                          </li>
                        @endfor
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-between p-2 p-xl-0 mt-xl-3 align-items-center">
                <a class="btn btn-link text-primary-hover p-0 mb-0 text-decoration-none"
                  href="{{ route("search") }}?{{ http_build_query(["value" => request()->query("value")]) }}">Hapus
                  Semua</a>
                <button class="btn btn-primary mb-0" type="submit">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
        <div class="left col-xl-9 col-12">
          <div class="ps-xl-6">
            <div class="search-header row g-2 g-xl-4 mb-4 flex-wrap">
              <div class="col-12 col-lg-6">
                <form method="GET">
                  <div class="rounded position-relative">
                    <input class="form-control bg-light pe-5 ps-3 py-2" type="search" name="value"
                      value="{{ request()->query("value") }}" placeholder="Cari produk berdasarkan nama..."
                      aria-label="Search">
                    <button
                      class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y"
                      type="submit"><i class="fa-solid fa-magnifying-glass fs-5 "></i></button>
                  </div>
                </form>
              </div>
              <div class="col-12 col-md-6 col-lg-3 ms-auto">
                <select class="form-select py-2" name="sort" aria-label="sort" @change="sorftBy($event)">
                  <option selected="">Urutkan</option>
                  <option value="asc">A-Z</option>
                  <option value="desc">Z-A</option>
                  <option value="latest">Terbaru</option>
                  <option value="oldest">Terlama</option>
                  <option value="lowest_price">Termurah</option>
                  <option value="highest_price">Termahal</option>
                  <option value="review">Ulasan</option>
                  <option value="bestsellers">Terlaris</option>
                </select>
              </div>
              <div class="col-md-6 col-lg-3 d-grid d-xl-none">
                <!-- Filter offcanvas button -->
                <button class="btn btn-dark mb-0" type="button" data-bs-toggle="offcanvas"
                  data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                  <i class="fas fa-sliders-h me-1"></i> Filter
                </button>
              </div>
            </div>
            <div class="search-list">
              <div class="row px-3 px-md-0 g-4">
                @if (count($products) > 0)
                  @foreach ($products as $product)
                    <div class="col-sm-12 col-md-6 col-lg-4">
                      <x-product-item :product="$product" />
                    </div>
                  @endforeach
                @else
                  <div class="text-center mt-4">
                    <img class="img-fluid p-4" src="/assets/img/illustrations/404-error.svg" alt=""
                      style="max-width: 400px" />
                    <p class="lead">Oops, {{ request()->query("value") ?? "produk" }} tidak ditemukan.</p>
                    <a class="text-arrow-icon small text-decoration-none d-flex align-items-center justify-content-center"
                      style="cursor: pointer;" @click.prevent="window.history.back()">
                      <i class="fa-solid fa-arrow-left me-1" style="font-size: 14px; transform:translateY(-.5px)"></i>
                      Kembali
                    </a>
                  </div>
                @endif
              </div>
              <div class="mt-4 pagination d-flex">
                {!! $products->withQueryString()->links("pagination::bootstrap-5") !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push("head")
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
      flex-wrap: wrap !important
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

@push("scripts")
  <script>
    function sorftBy(event) {
      const form = event.target.form;
      const params = new URLSearchParams(new FormData(form));

      window.location.href = `${form.action}?${params.toString()}`;
    }
  </script>
@endpush
