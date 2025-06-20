@extends('layouts.admin.app')

@section('content')
    <div class="container-xl mt-4">
        <nav class="nav nav-borders">
            <a class="nav-link ms-0 {{ Request::routeIs('admin.account.profile.index') ? 'active' : '' }}"
                href="{{ !Request::routeIs('admin.account.profile.index') ? route('admin.account.profile.index') : '#' }}">Profil</a>
            <a class="nav-link ms-0 {{ Request::routeIs('admin.account.security.index') ? 'active' : '' }}"
                href="{{ !Request::routeIs('admin.account.security.index') ? route('admin.account.security.index') : '#' }}">Keamanan</a>
        </nav>
        <hr class="mt-0 mb-4" />
        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.account.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-xl-4">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0" x-data="imagePreview('{{ Auth::user()->profile?->image
                        ? (filter_var(Auth::user()->profile->image, FILTER_VALIDATE_URL)
                            ? Auth::user()->profile->image
                            : asset('storage/' . Auth::user()->profile->image))
                        : '/assets/img/user-placeholder.svg' }}')">
                        <div class="card-header">Gambar Profil</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            <div class="d-flex justify-content-center mb-2">
                                <div class="ratio ratio-1x1  overflow-hidden w-100 rounded-circle"
                                    style="max-width: 200px; background-color: #f9f9f9">
                                    <div class="wrapper align-items-center d-flex justify-content-center">
                                        <img class="img-account-profile  w-100 h-100" style="object-fit: contain"
                                            :src="imageUrl" alt="" />
                                    </div>
                                </div>
                            </div>


                            <input type="file" name="image" hidden id="image"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" @change="previewFile($event)"
                                class="form-control file-input @error('image') is-invalid @enderror"
                                placeholder="Masukkan gambar spanduk Anda..." />
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-4">JPEG, JPG atau PNG tidak lebih besar dari 2 MB
                            </div>
                            <!-- Profile picture upload button-->
                            <label class="btn btn-primary form-control" for="image">Unggah gambar baru</label>
                            <div class="invalid-feedback">
                                @error('image')
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
                                <label class="small mb-1" for="username">Nama pengguna)</label>
                                <input class="form-control @error('username') is-invalid @enderror" id="username"
                                    name="username" type="text" placeholder="Masukkan nama pengguna Anda..."
                                    value="{{ old('username', $user->username) }}" />
                                <div class="invalid-feedback">
                                    @error('username')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <!-- Form Group (name)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="name">Nama</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" type="text" placeholder="Masukkan nama Anda..."
                                    value="{{ old('name', $user->name) }}" />
                                <div class="invalid-feedback">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <!-- Form Group (email address)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email</label>
                                <div class="d-flex gap-1 flex-column flex-md-row">
                                    <input class="form-control @error('email') is-invalid @enderror" name="email"
                                        id="email" type="email" placeholder="Masukkan email Anda..."
                                        value="{{ old('email', $user->email) }}" />
                                    <div class="{{ !$user->email_verified_at ? 'd-block' : 'd-none' }}">
                                        <button class="btn btn-danger" type="button" @click="verifyEmail">
                                            <span class="text-light text-nowrap">Verify Email</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <button class="btn btn-primary" type="submit">Simpan</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade show" id="innerFormModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('verification.send') }}" id="form_verify_email">
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
    </div>
@endsection

@push('scripts')
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
