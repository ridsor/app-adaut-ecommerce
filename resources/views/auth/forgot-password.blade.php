<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-header">
                  <h1 class="card-title">Lupa Password</h1>
                </div>
                <div class="card-body">
                  @if (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                      {{ Session::get('error') }}
                    </div>
                  @endif
                  <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label for="email" class="form-label">Email address</label>
                      <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}"
                        placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                      <div class="d-grid">
                        <button class="btn btn-primary">Kirim</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
    </div>
</x-guest-layout>
