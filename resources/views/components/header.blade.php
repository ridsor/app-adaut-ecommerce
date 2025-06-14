@props(['title' => 'Kembali', 'url' => route('home')])

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title d-flex gap-2">
                        <a class="btn btn-transparent-dark btn-icon" href="{{ $url }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-arrow-left">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg></a>
                        {{ $title }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>
