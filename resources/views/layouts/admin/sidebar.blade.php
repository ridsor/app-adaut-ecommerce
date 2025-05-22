<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu d-flex flex-column">
        <div class="nav accordion py-3" id="accordionSidenav" style="flex:1">
            <!-- Sidenav Accordion (Dashboard)-->
            <a class="nav-link  {{ request()->is('admin/dashboard/*') || request()->is('admin/dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>
            <a class="nav-link {{ request()->is('admin/kategori') || request()->is('admin/kategori/*') ? 'active' : '' }}"
                href="{{ route('category.index') }}">
                <div class="nav-link-icon"><i data-feather="list"></i></div>
                Kategori
            </a>
            <a class="nav-link {{ request()->is('admin/produk/*') || request()->is('admin/produk') ? 'active' : '' }}"
                href="{{ route('product.index') }}">
                <div class="nav-link-icon"><i data-feather="package"></i></div>
                Produk
            </a>
            <a class="nav-link  {{ request()->is('admin/spanduk') || request()->is('admin/spanduk/*') ? 'active' : '' }}"
                href="{{ route('banner.index') }}">
                <div class="nav-link-icon">
                    <svg fill="#a7aeb8" width="16px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 297 297" xml:space="preserve"
                        stroke="#a7aeb8">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <polygon points="79.801,180.101 106.091,211.171 106.091,180.101 "></polygon>
                                <polygon points="190.909,180.101 190.909,211.171 217.199,180.101 "></polygon>
                                <path
                                    d="M202.577,220.604h86.907c2.864,0,5.478-1.754,6.743-4.322c1.265-2.569,0.96-5.76-0.787-8.03l-17.721-22.996l17.539-20.981 c1.867-2.241,2.268-5.214,1.031-7.855c-1.237-2.641-3.89-4.039-6.806-4.039h-40.796v5.348c0,4.912-1.31,9.527-4.014,13.055 C234.754,183.726,202.577,220.604,202.577,220.604z">
                                </path>
                                <path
                                    d="M52.329,170.782c-2.987-3.755-4.599-8.576-4.599-13.63v-4.77H7.516c-2.916,0-5.569,1.398-6.806,4.039 c-1.237,2.641-0.835,5.614,1.031,7.855l17.539,20.981L1.56,208.252c-1.746,2.27-2.051,5.461-0.787,8.03 c1.265,2.569,3.88,4.322,6.743,4.322h86.906C94.423,220.604,62.481,183.542,52.329,170.782z">
                                </path>
                                <path
                                    d="M64.64,162.185c1.403,1.678,3.682,2.916,5.895,2.916h156.241c1.79,0,3.922-1.039,5.12-2.368 c1.148-1.275,1.792-3.403,1.792-5.003V84.346c0-4.151-3.654-7.95-7.805-7.95H70.535c-4.151,0-7.805,3.22-7.805,7.371v73.384 C62.73,158.918,63.52,160.845,64.64,162.185z">
                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
                Spanduk
            </a>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Masuk sebagai:</div>
                <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
            </div>
        </div>
</nav>
</div>
