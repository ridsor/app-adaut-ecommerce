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
                            <!-- Social forgot password form-->
                            <div class="card my-5">
                                <div class="card-body p-5 text-center">
                                    <div class="h3 fw-light mb-0">Pemulihan Kata Sandi</div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body p-5">
                                    <div class="text-center small text-muted mb-4">
                                        Masukkan alamat email Anda di bawah ini dan kami
                                        akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                                    </div>
                                    @if (Session::has('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif
                                    @if (Session::has('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ Session::get('status') }}
                                        </div>
                                    @endif
                                    <!-- Forgot password form-->
                                    <form method="POST" action="{{ route('password.email') }}">
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
                                        <!-- Form Group (reset password button)    -->
                                        <button class="btn btn-primary">Reset Password</button>
                                    </form>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body px-5 py-4">
                                    <div class="small text-center">
                                        Pengguna baru?
                                        <a href="auth-register-social.html">Buat akun!</a>
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
                        <div class="col-md-6 small">Copyright &copy; Your Website 2021</div>
                        <div class="col-md-6 text-md-end small">
                            <a href="#!">Privacy Policy</a>
                            &middot;
                            <a href="#!">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="/assets/js/bootsrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
