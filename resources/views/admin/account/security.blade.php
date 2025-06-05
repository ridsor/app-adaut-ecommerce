@extends("layouts.admin.app")

@section("content")
  <div class="container-xl mt-4">
    <nav class="nav nav-borders">
      <a class="nav-link ms-0 {{ Request::routeIs("admin.account.profile.index") ? "active" : "" }}"
        href="{{ !Request::routeIs("admin.account.profile.index") ? route("admin.account.profile.index") : "#" }}">Profil</a>
      <a class="nav-link ms-0 {{ Request::routeIs("admin.account.security.index") ? "active" : "" }}"
        href="{{ !Request::routeIs("admin.account.security.index") ? route("admin.account.security.index") : "#" }}">Keamanan</a>
    </nav>
    <hr class="mt-0 mb-4" />

    @if (Session::has("error"))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ Session::get("error") }}
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if (Session::has("success"))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get("success") }}
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <div class="col">
        <!-- Change password card-->
        <div class="card mb-4">
          <div class="card-header">Ubah Kata Sandi</div>
          <div class="card-body">
            <form method="POST" action="{{ route("password.update") }}">
              @csrf
              @method("PUT")
              <!-- Form Group (current password)-->
              <div class="mb-3">
                <label class="small mb-1" for="currentPassword">Kata Sandi
                  saat ini</label>
                <input class="form-control  @error("current_password") is-invalid @enderror" id="currentPassword"
                  name="current_password" type="password" placeholder="Masukan kata sandi saat ini..." />
                <div class="invalid-feedback">
                  @error("current_password")
                    {{ $message }}
                  @enderror
                </div>
              </div>

              <!-- Form Group (new password)-->
              <div class="mb-3">
                <label class="small mb-1" for="newPassword">Kata Sandi Baru</label>
                <input class="form-control @error("password") is-invalid @enderror" name="password" id="newPassword"
                  type="password" placeholder="Masukan kata sandi baru..." />
                <div class="invalid-feedback">
                  @error("password")
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <!-- Form Group (confirm password)-->
              <div class="mb-3">
                <label class="small mb-1" for="confirmPassword">Konfirmasi Kata Sandi</label>
                <input class="form-control @error("password_confirmation") is-invalid @enderror" id="confirmPassword"
                  type="password" name="password_confirmation" placeholder="Masukan konfirmasi kata sandi..." />
                <div class="invalid-feedback">
                  @error("password_confirmation")
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <button class="btn btn-primary" type="submit">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push("scripts")
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
      if (@json($errors->userDeletion->isNotEmpty())) {
        myModal.show();
      }
    });
  </script>
@endpush
