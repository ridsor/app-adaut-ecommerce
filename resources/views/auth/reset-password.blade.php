<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-header">
                  <h1 class="card-title">Reset Password</h1>
                </div>
                <div class="card-body">
                  @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                      @foreach ($errors->all() as $error)
                        {{ $error }}
                        <br />
                      @endforeach
                    </div>
                  @endif
                  <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <div class="mb-3">
                      <label for="email" class="form-label">Email address</label>
                      <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}"
                        placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" value="{{ old('password') }}" name="password" class="form-control" id="password"
                        required>
                    </div>
                    <div class="mb-3">
                      <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                      <input type="password" value="{{ old('password_confirmation') }}" name="password_confirmation"
                        class="form-control" id="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                      <div class="d-grid">
                        <button class="btn btn-primary">Simpan</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
    </div>
</x-guest-layout>
