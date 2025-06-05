<header x-ref="header" x-data="header()" :style="`height: ${height}px`" class="w-100">
  <div class="position-fixed z-3 w-100  bg-white" style="transition: all .3s ease-in-out"
    @scroll.window = "scrolled = (window.pageYOffset > 5)" id="header-content" x-ref="source"
    :class="scrolled ? 'shadow-sm' : ''">
    <div class="container py-2">
      <div class="d-flex justify-content-between align-items-center">
        <div class="right">
          <div class="d-flex align-items-center">
            <div class="px-2">
              <a href="{{ route("home") }}" class="text-decoration-none">
                <span class="fs-4 fw-bold">ADAUT</span>
              </a>
            </div>
          </div>
        </div>
        <div class="center" style="flex: 1; max-width: 600px">
          @unless (in_array(Route::currentRouteName(), ["search"]))
            <div class="search-responsive d-sm-none d-flex justify-content-end">
              <button class="btn p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_search"
                aria-controls="offcanvas_search">
                <x-icon>
                  <i class="fa-solid fa-magnifying-glass fs-4"></i>
                </x-icon>
              </button>
            </div>
            <div class="d-none d-sm-block">
              <form method="GET" action="{{ route("search") }}">
                <div class="d-flex align-items-center bg-light px-2 py-1 rounded-4">
                  <input type="search" class="form-control border-0 bg-transparent" name="search" placeholder="Cari..."
                    aria-label="Cari..." aria-describedby="btn-search">
                  <button class="py-2 px-3 border-0 bg-transparent" id="btn-search">
                    <i class="fa-solid fa-magnifying-glass fs-5 "></i>
                  </button>
                </div>
              </form>
            </div>
          @endunless
        </div>
        <div class="left">
          <div class="d-flex">
            <div class="cart position-relative" x-data>
              <button class="btn p-0" @click="handleShowCart" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvas_cart" aria-controls="offcanvas_cart">
                <x-icon>
                  <i class="fa-solid fa-cart-shopping fs-4"></i>
                </x-icon>
              </button>
              <div x-show="$store.cart.items.length > 0" style="top: 1rem; left: 80%"
                class="position-absolute pe-none translate-middle badge rounded-pill bg-danger">
                <span x-text="$store.cart.items.length > 100 ? '99+':$store.cart.items.length">99+</span>
              </div>
            </div>
            <div class="user">
              @auth
                <div class="dropdown">

                  <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <x-icon>
                      <img
                        src="{{ Auth::user()->profile?->image
                            ? (filter_var(Auth::user()->profile->image, FILTER_VALIDATE_URL)
                                ? Auth::user()->profile->image
                                : asset("storage/" . Auth::user()->profile->image))
                            : "/assets/img/user-placeholder.svg" }}"
                        alt="Profile" width="32" height="32" class="rounded-circle">
                    </x-icon>
                  </a>
                  <ul class="dropdown-menu text-small" style="">
                    <li><a class="dropdown-item d-flex align-items-center gap-1" href="{{ route("user.order.index") }}">
                        <div class="dropdown-item-icon p-1"><i class="fa-solid fa-basket-shopping"></i></div> <span>
                          Pesanan
                        </span>
                      </a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-1" href="{{ route("user.account.profile.index") }}">
                        <div class="dropdown-item-icon p-1"><i class="fa-solid fa-gear"></i></div> <span>
                          Akun
                        </span>
                      </a></li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <form method="POST" action="{{ route("logout") }}">
                        @csrf
                        <button class="dropdown-item d-flex align-items-center gap-1">
                        <div class="dropdown-item-icon p-1" style="transform: scaleX(-1)"><i class="fa-solid fa-arrow-right-to-bracket"></i></div> <span>
                          Logout
                        </span>
                      </button>
                      </form>
                    </li>
                  </ul>
                </div>
              @else
                <div class="dropdown">
                  <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-icon>
                      <i class="fa-solid fa-user fs-4"></i>
                    </x-icon>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item d-flex align-items-center gap-1" href="{{ route("login") }}">
                        <div class="dropdown-item-icon p-1"><i class="fa-solid fa-arrow-right-to-bracket"></i></div> <span>
                          Login
                        </span>
                      </a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-1" href="{{ route("register") }}">
                        <div class="dropdown-item-icon p-1">
                          <i class="fa-solid fa-user-plus"></i>
                        </div> <span>
                          Daftar
                        </span>

                      </a></li>

                  </ul>
                </div>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>

    @auth
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvas_cart"
        aria-labelledby="offcanvasCartLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasCartLabel">Keranjang
          </h5>
          <button type="button" class="btn btn-close shadow-none" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <x-cart />
        </div>
      </div>
    @endauth
    <div class="offcanvas offcanvas-top" data-bs-scroll="true" tabindex="-1" id="offcanvas_search"
      aria-labelledby="offcanvasSearchLabel">
      <div class="offcanvas-body">
        <form method="GET" action="{{ route("search") }}">
          <div class="d-flex align-items-center bg-light px-2 py-1 rounded-4">
            <input type="search" class="form-control border-0 bg-transparent" placeholder="Cari..." name="search"
              aria-label="Cari..." aria-describedby="btn-search">
            <button style="transform: translateY(2px)" class="py-2 px-3 border-0 bg-transparent" id="btn-search">
              <i class="fa-solid fa-magnifying-glass fs-5 "></i>
            </button>
            <button type="button" class="btn p-2 btn-close shadow-none" data-bs-dismiss="offcanvas"
              aria-label="Close"></button>
          </div>
        </form>
      </div>
    </div>
</header>

@push("scripts")
  <script>
    const header = function() {
      return {
        height: 0,
        scrolled: false,
        init() {
          const observer = new ResizeObserver(entries => {
            this.height = entries[0].contentRect.height;
          });
          observer.observe(this.$refs.source);
        }
      }
    }

    function handleShowCart() {
      if (@json(!Auth::check())) {
        window.location.href = '{{ route("login") }}'
        return
      }
    }
  </script>
@endpush
