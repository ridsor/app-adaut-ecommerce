@extends('layouts.user.app')

@section('content')
    <div class="container-xl p-0 px-md-2">
        <form
            action="{{ route('user.review.product.update', ['order_number' => request('order_number'), 'slug' => request('slug')]) }}"
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
                                <div class="d-flex gap-3 flex-column flex-md-row @error('rating') is-invalid @enderror"">
                                    <div>
                                        <div class="product-image ratio ratio-1x1 overflow-hidden" style="width: 80px">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt=""
                                                style="background-position: center" class="h-100 object-fit-contain" />
                                        </div>
                                    </div>
                                    <div class="rating">
                                        <div x-data="{
                                            rating: {{ old('rating', $review?->rating) ?? 0 }},
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
                                <div class="invalid-feedback">
                                    @error('rating')
                                        {{ $message }}
                                    @enderror
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
                                @php
                                    $mediaData =
                                        $review?->review_media
                                            ?->map(function ($media) {
                                                return [
                                                    'id' => $media->id,
                                                    'preview' => asset('storage/' . $media->file_path),
                                                ];
                                            })
                                            ->toArray() ?? [];
                                @endphp
                                <div x-data="mediaUpload()" x-init='imageFiles = @json($mediaData)'
                                    class="@error('photos') is-invalid @enderror">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="preview-container" x-show="imageFiles.length > 0">
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
                                        <div x-show="imageFiles.length < maxImage">
                                            <div class="border-1 text-gray-500 d-flex justify-content-center align-items-center"
                                                style="width: 100px; height: 100px; border-style: dotted"
                                                @click="$refs.imageInput.click()" @dragover.prevent="isDragging = true"
                                                @dragleave="isDragging = false" @drop.prevent="handleImageDrop($event)">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span><span x-text="imageFiles.length">0</span>/<span
                                                            x-text="maxImage">5</span></span>
                                                    <i class="fa-solid fa-camera" style="font-size: 30px"></i>
                                                </div>
                                            </div>
                                            <input type="hidden" name="deletedFiles[]" x-bind:value="deletedFiles" />
                                            <input type="file" name="photos[]" hidden id="photos"
                                                :disabled="imageFiles.length >= maxImage"
                                                accept=".jpg,.jpeg,.png,image/jpeg,image/png,image/webp" x-ref="imageInput"
                                                @change="handleImageSelect" multiple class="form-control file-input" />
                                        </div>
                                    </div>
                                    {{-- <div class="invalid-feedback">
                                        @error('photos')
                                            {{ $message }}
                                        @enderror
                                    </div> --}}
                                    {{-- {{ dd(session()->all()) }} --}}
                                    @if ($errors->get('photos.*'))
                                        <div class="mt-1">
                                            @foreach ($errors->get('photos.*') as $photoErrors)
                                                @foreach ($photoErrors as $message)
                                                    <div class='text-danger' style="font-size: 14px">{{ $message }}</div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="fw-500 btn btn-primary w-100">Simpan</button>
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
                deletedFiles: [],
                maxImage: 5,

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
                    const remainingSlots = this.maxImage - this.imageFiles.length;
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

                    // Process new files
                    newFiles.forEach(file => {
                        this.imageFiles.push({
                            file: file,
                            preview: URL.createObjectURL(file),
                            isExisting: true
                        });
                    });

                    // Update file input for form submission
                    const dataTransfer = new DataTransfer();
                    this.imageFiles
                        .filter(item => item.isExisting)
                        .forEach(item => dataTransfer.items.add(item.file));
                    this.$refs.imageInput.files = dataTransfer.files;
                },

                removeImage(index) {
                    // Hapus object URL untuk mencegah memory leak
                    if (this.imageFiles[index].preview) {
                        URL.revokeObjectURL(this.imageFiles[index].preview);
                    }
                    if (this.imageFiles[index].id) {
                        this.deletedFiles.push(this.imageFiles[index].id);
                    }
                    this.imageFiles.splice(index, 1);

                    // Update file input untuk form submission
                    const dataTransfer = new DataTransfer();
                    this.imageFiles
                        .filter(item => item.isExisting)
                        .forEach(item => dataTransfer.items.add(item.file));
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
