@extends('layouts.account.app')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!-- Change password card-->
            <div class="card mb-4">
                <div class="card-header">Ubah Kata Sandi</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <!-- Form Group (current password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="currentPassword">Kata Sandi
                                saat ini</label>
                            <input class="form-control  @error('current_password') is-invalid @enderror"
                                id="currentPassword" name="current_password" type="password"
                                placeholder="Masukan kata sandi saat ini..." />
                            <div class="invalid-feedback">
                                @error('current_password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <!-- Form Group (new password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="newPassword">Kata Sandi Baru</label>
                            <input class="form-control @error('password') is-invalid @enderror" name="password"
                                id="newPassword" type="password" placeholder="Masukan kata sandi baru..." />
                            <div class="invalid-feedback">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <!-- Form Group (confirm password)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="confirmPassword">Konfirmasi Kata Sandi</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="confirmPassword" type="password" name="password_confirmation"
                                placeholder="Masukan konfirmasi kata sandi..." />
                            <div class="invalid-feedback">
                                @error('password_confirmation')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Delete account card-->
            <div class="card mb-4">
                <div class="card-header">Hapus Akun</div>
                <div class="card-body">
                    <p>Menghapus akun Anda adalah tindakan permanen dan tidak dapat dibatalkan. Jika Anda yakin ingin
                        menghapus akun
                        Anda, pilih tombol di bawah ini.</p>
                    <button class="btn btn-danger-soft text-danger" data-bs-toggle="modal" data-bs-target="#confirmModal"
                        type="button">
                        Saya mengerti, hapus akun saya
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <form action="{{ route('account.security.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
            aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Hapus Akun</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="currentPassword">Masukan kata sandi untuk melanjutkan</label>
                            <input class="form-control  @if ($errors->userDeletion->has('password')) is-invalid @endif"
                                id="currentPassword" name="password" type="password" placeholder="kata sandi..." />
                            @if ($errors->userDeletion->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->userDeletion->get('password')[0] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-danger" type="button"
                            data-bs-dismiss="modal">Batal</button><button class="btn btn-success" type="submit">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
            if (@json($errors->userDeletion->isNotEmpty())) {
                myModal.show();
            }
        });
    </script>
@endpush
