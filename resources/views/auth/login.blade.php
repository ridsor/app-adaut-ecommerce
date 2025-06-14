@extends('layouts.app')

@section('content')
    <main style="margin-top: 100px">
        <div class="container-xxl p-0">
            <div class="auth-container">
                <section class="auth-image" aria-hidden="true"></section>

                <section class="auth-form-container">
                    <div class="logo d-flex align-items-center justify-content-center mb-4">
                        <!-- Uncomment the line below if you also wish to use an image logo -->
                        <img src="/assets/img/logo.png" style="height: 80px" alt="Logo">
                    </div>
                    @if (session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label visually-hidden">Email</label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" name="email" placeholder="Email" autocomplete="email"
                                aria-describedby="emailHelp" />
                            <div class="invalid-feedback">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 position-relative" x-data="{ show: false }">
                            <label for="password" class="form-label visually-hidden">Password</label>
                            <div class="position-relative @error('password') is-invalid @enderror">
                                <input class="form-control form-control-solid" :type="show ? 'text' : 'password'"
                                    id="password" name="password" placeholder="Password" value="{{ old('password') }}"
                                    aria-label="Password" aria-describedby="passwordExample" />

                                <button type="button" @click="show = !show"
                                    class="position-absolute end-0 translate-middle-y me-2 border-0 bg-transparent top-50">
                                    <!-- Icon mata terbuka -->
                                    <i x-show="!show" class="bi bi-eye-fill"></i>
                                    <!-- Icon mata tertutup -->
                                    <i x-show="show" class="bi bi-eye-slash-fill"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-options">
                            <label>
                                <input type="checkbox" id="rememberMe" name="remember" /> Ingat saya
                            </label>
                            <a href="{{ route('password.request') }}">Lupa password?</a>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary w-100 fw-bold">
                            Masuk
                        </button>
                    </form>

                    <div class="divider" aria-label="Alternative sign up options">
                        atau
                    </div>

                    <div class="social-login d-flex justify-content-center">
                        <a class="btn btn-icon btn-google mx-1 text-white" href="{{ route('auth.google.redirect') }}"><i
                                class="fab fa-google fa-fw fa-sm"></i></a>
                    </div>

                    <div class="px-2 mt-5">
                        <div class="small text-center">
                            Pengguna baru?
                            <a href="{{ route('register') }}">Buat akun!</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection

@push('head')
    <link href="/assets/css/auth.css" rel="stylesheet" />
@endpush
