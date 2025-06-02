@extends('layouts.user.app')

@section('content')
    <div class="container-xl p-0 px-md-2">
        <form
            action="{{ route('user.review.product.show', ['order_number' => request('order_number'), 'slug' => request('slug')]) }}"
            method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="row gx-4">
                <div class="col">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">
                            Nilai Produk
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex gap-3 flex-column flex-md-row">
                                    <div>
                                        <div class="product-image ratio ratio-1x1 overflow-hidden" style="width: 80px">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt=""
                                                style="background-position: center" class="h-100 object-fit-contain" />
                                        </div>
                                    </div>
                                    <div class="rating">
                                        <div x-data="{
                                            rating: 0,
                                            hoverRating: 0,
                                            ratings: [{ 'amount': 1, 'label': 'Sangat Buruk' }, { 'amount': 2, 'label': 'Buruk' }, { 'amount': 3, 'label': 'Biasa' }, { 'amount': 4, 'label': 'Bagus' }, { 'amount': 5, 'label': 'Sangat Bagus' }],
                                        
                                            rate(amount) {
                                                this.rating = amount;
                                                // Update hidden input value
                                                this.$refs.ratingInput.value = amount;
                                            },
                                        
                                            currentLabel() {
                                                if (this.hoverRating !== 0) {
                                                    return this.ratings.find(r => r.amount === this.hoverRating).label;
                                                }
                                        
                                                if (this.rating !== 0) {
                                                    return this.ratings.find(r => r.amount === this.rating).label;
                                                }
                                        
                                                return 'Berikan rating';
                                            }
                                        }">
                                            <input type="hidden" name="rating" x-ref="ratingInput" x-model="rating"
                                                value="0">

                                            <div class="text-gray-500 mb-1 fs-responsive">
                                                Kualitas Produk
                                            </div>
                                            <div class="d-flex align-items-md-center gap-2 flex-column flex-md-row">
                                                <div class="d-flex mb-2">
                                                    <template x-for="(star, index) in ratings" :key="index">
                                                        <button type="button" @click="rate(star.amount)"
                                                            @mouseover="hoverRating = star.amount"
                                                            @mouseleave="hoverRating = 0"
                                                            class="text-3xl focus:outline-none bg-transparent border-0 p-1">
                                                            <img :src="hoverRating >= star.amount || rating >= star
                                                                .amount ? '/icons/rate.svg' : '/icons/nonrate.svg'"
                                                                alt="star" style="width: 30px; height: 30px;">
                                                        </button>
                                                    </template>
                                                </div>

                                                <div class="text-gray-600 fs-responsive" x-text="currentLabel()"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea id="comment" class="lh-base form-control @error('comment') is-invalid @enderror" type="text"
                                    name="comment" rows='4' placeholder="Bagikan penilaianmu tentang produk ini">{{ old('comment', $review?->comment) }}</textarea>
                                <div class="invalid-feedback">
                                    @error('comment')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="text-gray-500 mb-1 fs-responsive">
                                    Foto
                                </div>
                                <div x-data="mediaUpload()" x-init="imageFiles = {{ $review?->review_media }}">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="preview-containe" x-show="imageFiles.length > 0">
                                            <div class="d-flex gap-2 flex-wrap">
                                                <template x-for="(file, index) in imageFiles" :key="index">
                                                    <div class="product-image overflow-hidden position-relative"
                                                        style="width: 100px; height: 100px">
                                                        <img :src="file.preview" alt="Preview gambar"
                                                            style="object-position: center; object-fit: cover"
                                                            class="w-100 h-100" />
                                                        <button type="button" @click="removeImage(index)"
                                                            class="text-white position-absolute top-0 end-0 p-0 border-0 d-flex justify-content-center align-items-center"
                                                            style="z-index: 1; width: 20px; aspect-ratio: 1/1; background-color: rgba(0,0,0,.5)">
                                                            <i class="fa-solid fa-x"></i>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="border-1 text-gray-500 d-flex justify-content-center align-items-center"
                                            style="width: 100px; height: 100px; border-style: dotted"
                                            @click="$refs.imageInput.click()" @dragover.prevent="isDragging = true"
                                            @dragleave="isDragging = false" @drop.prevent="handleImageDrop($event)">
                                            <i class="fa-solid fa-camera" style="font-size: 30px"></i>
                                        </div>
                                        <input type="file" name="photos" hidden id="photos"
                                            accept=".jpg,.jpeg,.png,image/jpeg,image/png,image/webp" x-ref="imageInput"
                                            @change="handleImageSelect" multiple
                                            class="form-control file-input @error('photos') is-invalid @enderror" />
                                        <div class="invalid-feedback">
                                            @error('photos')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="fw-500 w-100 btn btn-primary">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <style>
        .list-item .item .dropdown button::after {
            display: none !important
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('mediaUpload', () => ({
                isDragging: false,
                isDraggingVideo: false,
                imageFiles: [],
                videoFile: null,

                handleImageSelect(event) {
                    const files = Array.from(event.target.files);
                    this.addImages(files);
                },

                handleImageDrop(event) {
                    this.isDragging = false;
                    const files = Array.from(event.dataTransfer.files)
                        .filter(file => file.type.startsWith('image/'));
                    this.addImages(files);
                },

                addImages(files) {
                    const remainingSlots = 5 - this.imageFiles.length;
                    if (remainingSlots <= 0) {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Anda hanya dapat mengupload maksimal 5 gambar",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        return;
                    }

                    const newFiles = files.slice(0, remainingSlots);

                    // Tambahkan preview URL ke setiap file
                    newFiles.forEach(file => {
                        file.preview = URL.createObjectURL(file);
                    });

                    this.imageFiles.push(...newFiles);

                    // Update file input untuk form submission
                    const dataTransfer = new DataTransfer();
                    this.imageFiles.forEach(file => dataTransfer.items.add(file));
                    this.$refs.imageInput.files = dataTransfer.files;
                },

                removeImage(index) {
                    // Hapus object URL untuk mencegah memory leak
                    URL.revokeObjectURL(this.imageFiles[index].preview);
                    this.imageFiles.splice(index, 1);

                    // Update file input untuk form submission
                    const dataTransfer = new DataTransfer();
                    this.imageFiles.forEach(file => dataTransfer.items.add(file));
                    this.$refs.imageInput.files = dataTransfer.files;
                },

                // ... (fungsi untuk video tetap sama seperti sebelumnya) ...

                // Bersihkan object URL saat komponen di-unmount
                destroy() {
                    this.imageFiles.forEach(file => {
                        URL.revokeObjectURL(file.preview);
                    });
                    if (this.videoFile && this.videoFile.preview) {
                        URL.revokeObjectURL(this.videoFile.preview);
                    }
                }
            }))
        })
    </script>
@endpush
