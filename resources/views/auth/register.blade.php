@extends('layouts.auth.app')

@section('content')
    <main>
        <div class="container-xl px-4">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <!-- Social registration form-->
                    <div class="card my-5">
                        <div class="card-body p-5 text-center">
                            <div class="h3 fw-light mb-3">Buat Akun</div>
                            <div class="small text-muted mb-2">Masuk dengan...</div>
                            <!-- Social registration links-->
                            <a class="btn btn-icon btn-google mx-1" href="{{ route('auth.google.redirect') }}"><i
                                    class="fab fa-google fa-fw fa-sm"></i></a>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body p-5">
                            <div class="text-center small text-muted mb-4">
                                ... atau masukkan informasi Anda di bawah ini.
                            </div>
                            @if (session()->has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            <!-- Login form-->
                            <form method="POST" action="{{ route('register.post') }}">
                                @csrf
                                <!-- Form Row-->
                                <div class="row gx-3">
                                    <!-- Form Group (name)-->
                                    <div class="mb-3">
                                        <label class="text-gray-600 small" for="emailExample">Nama</label>
                                        <input class="form-control form-control-solid @error('name') is-invalid @enderror"
                                            type="text" placeholder="" name="name" value="{{ old('name') }}"
                                            aria-label="Email Address" aria-describedby="emailExample" />
                                        <div class="invalid-feedback">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Form Group (email address)-->
                                    <div class="mb-3">
                                        <label class="text-gray-600 small" for="emailExample">Email
                                            address</label>
                                        <input class="form-control form-control-solid @error('email') is-invalid @enderror"
                                            type="text" placeholder="" name="email" value="{{ old('email') }}"
                                            aria-label="Email Address" aria-describedby="emailExample" />
                                        <div class="invalid-feedback">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Form Row-->
                                    <div class="row gx-3">
                                        <div class="col-md-6">
                                            <!-- Form Group (choose password)-->
                                            <div class="mb-3">
                                                <label class="text-gray-600 small" for="passwordExample">Password</label>
                                                <input
                                                    class="form-control form-control-solid @error('password') is-invalid @enderror"
                                                    type="password" name="password" placeholder=""
                                                    value="{{ old('password') }}" aria-label="Password"
                                                    aria-describedby="passwordExample" />
                                                <div class="invalid-feedback">
                                                    @error('password')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Form Group (confirm password)-->
                                            <div class="mb-3">
                                                <label class="text-gray-600 small" for="confirmPasswordExample">Konfirmasi
                                                    Password</label>
                                                <input
                                                    class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror"
                                                    type="password" name="password_confirmation" placeholder=""
                                                    aria-label="Confirm Password"
                                                    aria-describedby="confirmPasswordExample" />
                                                <div class="invalid-feedback">
                                                    @error('password_confirmation ')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group (form submission)-->
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button class="btn btn-primary w-100">Buat Akun</button>
                                    </div>
                            </form>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body px-5 py-4">
                            <div class="small text-center">
                                Punya akun?
                                <a href="{{ route('login') }}">Masuk!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
