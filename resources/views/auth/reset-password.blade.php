@extends('layouts.auth.app')

@section('content')
    <main>
        <div class="container-xl px-4">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <!-- Social forgot password form-->
                    <div class="card my-5">
                        <div class="card-body p-5 text-center">
                            <div class="h3 fw-light mb-0">Setel Ulang Kata Sandi</div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body p-5">
                            <div class="text-center small text-muted mb-4">
                                Buat kata sandi yang kuat untuk akun dengan e-mail
                                {{ request()->query('email') }}
                            </div>
                            @if (Session::has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            <!-- Forgot password form-->
                            <form method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="text-gray-600 small" for="email">Email address</label>
                                    <input class="form-control form-control-solid @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" type="text" placeholder="" aria-label="Email Address"
                                        aria-describedby="email" name="email" value="" id="email" />
                                    <div class="invalid-feedback">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-600 small" for="password">Password</label>
                                    <input class="form-control form-control-solid @error('password') is-invalid @enderror"
                                        value="{{ old('password') }}" type="password" placeholder="" name="password"
                                        aria-label="Password" id="password" aria-describedby="passwordExample" />
                                    <div class="invalid-feedback">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-600 small" for="password_confirmation">Konfirmasi
                                        Password</label>
                                    <input
                                        class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror"
                                        value="{{ old('password_confirmation') }}" type="password" placeholder=""
                                        name="password_confirmation" aria-label="password_confirmation"
                                        id="password_confirmation" aria-describedby="password_confirmation" />
                                    <div class="invalid-feedback">
                                        @error('password_confirmation')
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
