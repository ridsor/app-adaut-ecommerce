@extends("layouts.account.app")

@section("content")
  <form method="POST" action="{{ route("account.profile.update") }}" enctype="multipart/form-data">
    @csrf
    @method("PATCH")
    <div class="row">
      <div class="col-xl-4">
        <!-- Profile picture card-->
        <div class="card mb-4 mb-xl-0" x-data="imagePreview('{{ $user->profile?->image ? asset("storage/" . $user->profile?->image) : "/assets/img/illustrations/profiles/profile-2.png" }}')">
          <div class="card-header">Gambar Profil</div>
          <div class="card-body text-center">
            <!-- Profile picture image-->
            <img class="img-account-profile rounded-circle mb-2" :src="imageUrl" alt="" />

            <input type="file" name="image" hidden id="image"
              accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" @change="previewFile($event)"
              class="form-control file-input @error("image") is-invalid @enderror"
              placeholder="Masukkan gambar spanduk Anda..." />
            <!-- Profile picture help block-->
            <div class="small font-italic text-muted mb-4">JPEG, JPG atau PNG tidak lebih besar dari 2 MB</div>
            <!-- Profile picture upload button-->
            <label class="btn btn-primary form-control" for="image">Unggah gambar baru</label>
            <div class="invalid-feedback">
              @error("image")
                {{ $message }}
              @enderror
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-8">
        <!-- Account details card-->
        <div class="card mb-4">
          <div class="card-header">Detail Akun</div>
          <div class="card-body">
            <!-- Form Group (username)-->
            <div class="mb-3">
              <label class="small mb-1" for="username">Nama pengguna (bagaimana nama Anda akan terlihat oleh
                pengguna lain di situs)</label>
              <input class="form-control @error("username") is-invalid @enderror" id="username" name="username"
                type="text" placeholder="Masukkan nama pengguna Anda..."
                value="{{ old("username", $user->username) }}" />
              <div class="invalid-feedback">
                @error("username")
                  {{ $message }}
                @enderror
              </div>
            </div>
            <!-- Form Group (name)-->
            <div class="mb-3">
              <label class="small mb-1" for="name">Name</label>
              <input class="form-control @error("name") is-invalid @enderror" id="name" name="name" type="text"
                placeholder="Masukkan nama Anda..." value="{{ old("name", $user->name) }}" />
              <div class="invalid-feedback">
                @error("name")
                  {{ $message }}
                @enderror
              </div>
            </div>
            <!-- Form Group (email address)-->
            <div class="mb-3">
              <label class="small mb-1" for="email">Email</label>
              <div class="d-flex gap-1 flex-column flex-md-row">
                <input class="form-control @error("email") is-invalid @enderror" name="email" id="email"
                  type="email" placeholder="Masukkan email Anda..." value="{{ old("email", $user->email) }}" />
                <div class="{{ !$user->email_verified_at ? "d-block" : "d-none" }}">
                  <button class="btn btn-danger" type="button" @click="verifyEmail">
                    <span class="text-light text-nowrap">Verify Email</span>
                  </button>
                </div>
              </div>
              <div class="invalid-feedback">
                @error("email")
                  {{ $message }}
                @enderror
              </div>
            </div>
            <!-- Form Group (phone number)-->
            <div class="mb-3">
              <label class="small mb-1" for="phone_number">Nomor Telepon</label>
              <input class="form-control @error("phone_number") is-invalid @enderror" id="phone_number"
                name="phone_number" type="text" placeholder="Masukkan nomor telepon Anda..."
                value="{{ old("phone_number", $user->profile?->phone_number) }}" />
              <div class="invalid-feedback">
                @error("phone_number")
                  {{ $message }}
                @enderror
              </div>
            </div>
            <!-- Form Row-->
            <div class="row gx-3 mb-3">
              <!-- Form Group (gender)-->
              <div class="col-md-6">
                <label class="small mb-1" for="gender">Jenis Kelamin</label>
                <select name="gender" id="gender" class="form-control @error("gender") is-invalid @enderror">
                  <option value="" selected>Jenis Kelamin</option>
                  @foreach ($genders as $gender)
                    @if ($gender === old("gender", $user->profile?->gender))
                      <option value="{{ $gender }}" selected>{{ $gender }}</option>
                    @else
                      <option value="{{ $gender }}">{{ $gender }}</option>
                    @endif
                  @endforeach
                </select>
                <div class="invalid-feedback">
                  @error("name")
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <!-- Form Group (date_of_birth)-->
              <div class="col-md-6">
                <label class="small mb-1" for="date_of_birth">Tanggal Lahir</label>
                <input class="form-control @error("date_of_birth") is-invalid @enderror" id="date_of_birth" type="date"
                  name="date_of_birth"
                  value="{{ old("date_of_birth", $user->profile?->date_of_birth ? $user->profile?->date_of_birth->format("Y-m-d") : "") }}"
                  placeholder="Masukan tanggal lahir Anda...">
                <div class="invalid-feedback">
                  @error("date_of_birth")
                    {{ $message }}
                  @enderror
                </div>
              </div>
            </div>
            <!-- Save changes button-->
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>

          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Modal -->
  <div class="modal fade show" id="innerFormModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="{{ route("verification.send") }}" id="form_verify_email">
          @csrf
          <div class="modal-body">
            <p>Verifikasi email anda</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger">
              <span class="text-light text-nowrap">Verify Email</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push("scripts")
  <script>
    function verifyEmail() {
      const form = (document.getElementById('form_verify_email'));
      form.submit()
    }

    function imagePreview(defaultImageUrl = '') {
      return {
        imageUrl: defaultImageUrl,
        defaultImageUrl: defaultImageUrl,
        fileName: 'Tidak ada file dipilih',

        previewFile(event) {
          const file = event.target.files[0];
          if (!file || !file.type.includes('image')) {
            this.imageUrl = '';
            this.fileName = 'Tidak ada file dipilih';
            return;
          }

          // Buat FileReader untuk membaca file
          const reader = new FileReader();

          // Setup handler untuk event onload
          reader.onload = (e) => {
            this.imageUrl = e.target.result;
          };

          // Baca file sebagai data URL
          reader.readAsDataURL(file);

          this.fileName = file.name || 'Tidak ada file dipilih'
        },
        resetToDefault() {
          this.imageUrl = this.defaultImageUrl;
          document.getElementById('image-input').value = '';
        }
      }
    }
  </script>
@endpush
