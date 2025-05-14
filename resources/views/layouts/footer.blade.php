<footer>
    <div class="container">
        <div class="py-5">
            <div class="d-flex gap-2 justify-content-between flex-column flex-md-row">
                <div>
                    <div class="logo">
                        <span class="fs-4 fw-bold mb-3">ADAUT</span>
                    </div>
                </div>
                <div style="flex: 1">
                    <div class="d-flex flex-column gap-2 ms-0 ms-md-4">
                        <div>
                            <a class="text-decoration-none text-secondary" href="{{ route('search') }}?sort=bestsellers">
                                Penjualan Terbaik
                            </a>
                        </div>
                        <div>
                            <a class="text-decoration-none text-secondary" href="{{ route('search') }}?sort=latest">
                                Rilisan Baru
                            </a>
                        </div>
                        <div>
                            <a class="text-decoration-none text-secondary"
                                href="{{ route('search') }}?sort=lowest_price">
                                Harga Termurah
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-column mt-5 mt-md-0">
                    <p class="mb-4">
                        <i class="fa-solid fa-phone"></i> +62 823 4567 8901
                    </p>
                    <div class="social-media d-flex align-items-center gap-2">
                        <a href="#">
                            <div class="btn btn-outline-light px-3">
                                <i class="fa-brands fa-facebook-f" style="font-size: 13px"></i>
                            </div>
                        </a>
                        <a href="#">
                            <div class="btn btn-outline-light px-3">
                                <i class="fa-brands fa-x-twitter"></i>
                            </div>
                        </a>
                        <a href="#">
                            <div class="btn btn-outline-light px-3">
                                <i class="fa-brands fa-pinterest"></i>
                            </div>
                        </a>
                        <a href="#">
                            <div class="btn btn-outline-light px-3">
                                <i class="fa-brands fa-instagram"></i>
                            </div>
                        </a>
                        <a href="#">
                            <div class="btn btn-outline-light px-3">
                                <i class="fa-brands fa-youtube"></i>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <p class="text-center">
            © 2023 Adaut. All rights reserved.
        </p>
    </div>
</footer>
