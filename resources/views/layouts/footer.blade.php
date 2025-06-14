<footer class="footer light-background pt-5" id="footer">
    <div class="container">
        <div class="mb-4">
            <div class="row d-flex justify-content-between flex-wrap">
                <div class="col-sm-6 col-lg-4">
                    <div class="mb-4">
                        <div class="logo d-flex align-items-center me-auto me-xl-0 text-decoration-none">
                            <!-- Uncomment the line below if you also wish to use an image logo -->
                            <img src="/assets/img/logo.png" alt="Logo">
                        </div>
                    </div>
                    <a href="{{ route('contact-us') }}" class="text-decoration-none">
                        <p>
                            <span>
                                Hubungi Kami
                            </span>
                        </p>
                    </a>
                    <p>
                        <i class="fa-solid fa-phone"></i>
                        <span class="ms-1">
                            +62 823 4567 8901
                        </span>
                    </p>
                    <p>
                        <i class="fa-solid fa-envelope"></i>
                        <span class="ms-1">
                            adaut@adaut.com
                        </span>
                    </p>
                </div>
                <div class="col-sm-6 col-lg-4 mt-5 mt-sm-0">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a class="text-decoration-none text-secondary"
                                href="{{ route('search') }}?sort=bestsellers">
                                Penjualan Terlaris
                            </a>
                        </div>
                        <div>
                            <a class="text-decoration-none text-secondary" href="{{ route('search') }}?sort=review">
                                Ulasan Terbaik
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
                <div class="d-flex flex-column col-lg-4 col-12 mt-5 mt-lg-0">
                    <div class="fw-bold fs-6 mb-2">
                        Ikuti Kami
                    </div>
                    <p>
                        Dukungan Anda sangat berarti bagi kami. Klik ikuti dan jadilah bagian dari perjalanan kami!
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
        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Adaut</strong> <span>All Rights
                    Reserved</span></p>
        </div>
    </div>
</footer>
