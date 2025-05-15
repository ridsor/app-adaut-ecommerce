<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    <link href="/assets/css/styles.css" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                            <!-- Social login form-->
                            <div class="card my-5">
                                <div class="card-body p-5 text-center">
                                    <div class="h3 fw-light mb-3">Masuk</div>
                                    <!-- Social login links-->
                                    <a class="btn btn-icon btn-google mx-1" href="#!"><i
                                            class="fab fa-google fa-fw fa-sm"></i></a>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session()->get('error') }}
                                        </div>
                                    @endif
                                    <!-- Login form-->
                                    <form method="POST" action="{{ route('login.post') }}">
                                        @csrf
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="emailExample">Email address</label>
                                            <input
                                                class="form-control form-control-solid @error('email') is-invalid @enderror"
                                                type="text" placeholder="" aria-label="Email Address"
                                                aria-describedby="emailExample" name="email" />
                                            <div class="invalid-feedback">
                                                @error('email')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="text-gray-600 small" for="passwordExample">Password</label>
                                            <input
                                                class="form-control form-control-solid @error('password') is-invalid @enderror"
                                                type="password" placeholder="" name="password" aria-label="Password"
                                                aria-describedby="passwordExample" />
                                            <div class="invalid-feedback">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Form Group (forgot password link)-->
                                        <div class="mb-3"><a class="small"
                                                href="{{ route('password.request') }}">Lupa password?</a></div>
                                        <!-- Form Group (login box)-->
                                        <div class="d-flex align-items-center justify-content-between mb-0">
                                            <div class="form-check">
                                                <input class="form-check-input" id="checkRememberPassword"
                                                    type="checkbox" name="remember" />
                                                <label class="form-check-label" for="checkRememberPassword">Ingat
                                                    saya</label>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body px-5 py-4">
                                    <div class="small text-center">
                                        Pengguna baru?
                                        <a href="{{ route('register') }}">Buat akun!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">© 2025 Adaut. All rights reserved.</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="/assets/js/bootsrap.bundle.min.js">
    </script>
    <script src="/assets/js/scripts.js"></script>
</body>

</html>
