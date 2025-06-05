@extends('layouts.auth.app')

@section('content')
    <main>
        <div class="container-xl px-1 px-sm-4">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <!-- Social login form-->
                    <div class="card my-5">
                        <div class="card-body p-5 text-center">
                            <div class="h3 fw-light mb-3">Masuk</div>
                            <!-- Social login links-->
                            <a class="btn btn-icon btn-google mx-1" href="{{ route('auth.google.redirect') }}"><i
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
                                    <input class="form-control form-control-solid @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
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
                                    <input class="form-control form-control-solid @error('password') is-invalid @enderror"
                                        type="password" placeholder="" name="password" aria-label="Password"
                                        aria-describedby="passwordExample" />
                                    <div class="invalid-feedback">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <!-- Form Group (forgot password link)-->
                                <div class="mb-3"><a class="small" href="{{ route('password.request') }}">Lupa
                                        password?</a></div>
                                <!-- Form Group (login box)-->
                                <div class="d-flex align-items-center justify-content-between mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" id="checkRememberPassword" type="checkbox"
                                            name="remember" />
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
@endsection
