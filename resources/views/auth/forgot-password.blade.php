@extends('layouts.auth.app')

@section('content')
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
                                    <input class="form-control form-control-solid @error('email') is-invalid @enderror"
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
@endsection
