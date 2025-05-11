@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

<header x-ref="header" x-data="header()" :style="`height: ${height}px`" class="w-100">
    <div class="position-fixed z-3 w-100  bg-white" style="transition: all .3s ease-in-out"
        @scroll.window = "scrolled = (window.pageYOffset > 5)" id="header-content" x-ref="source"
        :class="scrolled ? 'shadow-sm' : ''">
        <div class="container py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="right">
                    <div class="d-flex align-items-center">
                        <div class="px-2">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <span class="fs-4 fw-bold">ADAUT</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="center" style="flex: 1; max-width: 600px">
                    <div class="search-responsive d-sm-none d-flex justify-content-end">
                        <button class="btn p-0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas_search" aria-controls="offcanvas_search">
                            <x-icon>
                                <i class="fa-solid fa-magnifying-glass fs-4"></i>
                            </x-icon>
                        </button>
                    </div>
                    <div class="d-none d-sm-block">
                        <form method="GET" action="{{ route('search') }}">
                            <div class="d-flex align-items-center bg-light px-2 py-1 rounded-4">
                                <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari..."
                                    aria-label="Cari..." aria-describedby="btn-search">
                                <button class="py-2 px-3 border-0 bg-transparent" id="btn-search">
                                    <i class="fa-solid fa-magnifying-glass fs-5 "></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="left">
                    <div class="d-flex">
                        <div class="cart position-relative">
                            <button class="btn p-0" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvas_cart" aria-controls="offcanvas_cart">
                                <x-icon>
                                    <i class="fa-solid fa-cart-shopping fs-4"></i>
                                </x-icon>
                            </button>
                            <span style="top: 1rem; left: 80%"
                                class="position-absolute pe-none translate-middle badge rounded-pill bg-danger">
                                99+
                            </span>
                        </div>

                        <div class="user">
                            <div class="dropdown">
                                <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <x-icon>
                                        <i class="fa-solid fa-user fs-4"></i>
                                    </x-icon>
                                </button>
                                <ul class="dropdown-menu">
                                    @auth
                                        <li><a class="dropdown-item" href="{{ route('login') }}">Profil</a></li>
                                        <li><a class="dropdown-item" href="{{ route('register') }}">Pesanan</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                                        <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                                    @endauth
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvas_cart"
            aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-header">
                {{-- <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdrop with scrolling</h5> --}}
                <button type="button" class="btn btn-close shadow-none" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p>Cart</p>
            </div>
        </div>
        <div class="offcanvas offcanvas-top" data-bs-scroll="true" tabindex="-1" id="offcanvas_search"
            aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-body">
                <form method="GET" action="{{ route('search') }}">
                    <div class="d-flex align-items-center bg-light px-2 py-1 rounded-4">
                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari..."
                            aria-label="Cari..." aria-describedby="btn-search">
                        <button style="transform: translateY(2px)" class="py-2 px-3 border-0 bg-transparent"
                            id="btn-search">
                            <i class="fa-solid fa-magnifying-glass fs-5 "></i>
                        </button>
                        <button type="button" class="btn p-2 btn-close shadow-none" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                </form>
            </div>
        </div>
</header>

@push('scripts')
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
    </script>
@endpush
