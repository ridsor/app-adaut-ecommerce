{{-- {{ dd(request()->session()->all()) }} --}}
<x-layout title="Paket Wisata">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title">Edit Paket Wisata</h1>
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
            <form action="{{ route('tour_package.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" id="name"
                  value="{{ old('name', $tour_package->name) }}" required>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" id="price"
                  value="{{ old('price', $tour_package->price) }}" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea required name="description" id="description" cols="30" rows="3" class="form-control">{{ old('description', $tour_package->description) }}</textarea>
              </div>
              <div class="mb-3">
                <label for="duration" class="form-label">Durasi</label>
                <input type="number" name="duration" class="form-control" id="duration"
                  value="{{ old('duration', $tour_package->duration) }}" required>
              </div>
              <div class="mb-3">
                <label for="min_age" class="form-label">Umur Minimum</label>
                <input type="number" name="min_age" class="form-control" id="min_age"
                  value="{{ old('min_age', $tour_package->min_age) }}" required>
              </div>
              <div class="mb-3">
                <label for="max_people" class="form-label">Orang Maksimum</label>
                <input type="number" name="max_people" class="form-control" id="max_people"
                  value="{{ old('max_people', $tour_package->max_people) }}" required>
              </div>
              <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" id="location"
                  value="{{ old('location', $tour_package->location) }}" required>
              </div>
              <div class="mb-3">
                <label for="availability" class="form-label">Ketersediaan</label>
                <input type="text" name="availability" class="form-control" id="availability"
                  value="{{ old('availability', $tour_package->availability) }}" required>
              </div>
              <div class="mb-3">
                <label for="departure_location" class="form-label">Lokasi Penjemputan</label>
                <input type="text" name="departure_location" class="form-control" id="departure_location"
                  value="{{ old('departure_location', $tour_package->departure_location) }}" required>
              </div>
              <div class="mb-3">
                <label for="return_location" class="form-label">Lokasi Pengembalian</label>
                <input type="text" name="return_location" class="form-control" id="return_location"
                  value="{{ old('return_location', $tour_package->return_location) }}" required>
              </div>
              <div class="mb-3">
                <label for="facility" class="form-label">Fasilitas</label>
                <input type="text" name="facility" class="form-control" id="facility"
                  value="{{ old('facility', $tour_package->facility) }}" required>
              </div>
              <div class="mb-3">
                <label for="nonfacility" class="form-label">Bukan Fasilitas</label>
                <input type="text" name="nonfacility" class="form-control" id="nonfacility"
                  value="{{ old('nonfacility', $tour_package->nonfacility) }}" required>
              </div>
              <div class="mb-3">
                <label for="to_bring" class="form-label">Yang Perlu Dibawa</label>
                <input type="text" name="to_bring" class="form-control" id="to_bring"
                  value="{{ old('to_bring', $tour_package->to_bring) }}" required>
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="expectations" class="form-label">Apa Yang Diharapkan</label>
                <textarea required name="expectations" id="description" cols="30" rows="3" class="form-control">{{ old('expectations', $tour_package->expectations) }}</textarea>
              </div>
              <div class="mb-3" x-data="imagePreview('{{ old('image', $tour_package->image) }}')">
                <label for="image" class="form-label">Gambar</label>
                <input type="file" name="image" id="image" class="form-control" required accept="image/*"
                  @change="previewFile($event)">
                <div class="preview-container" :class="{ 'hidden': !imageUrl }">
                  <img id="preview-image" :src="imageUrl" alt="Preview gambar">
                </div>
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
  @push('head')
    <style>
      #preview-image {
        max-width: 300px;
        max-height: 300px;
        margin: 10px auto;
        display: block;
      }

      .preview-container {
        margin-top: 15px;
      }

      .hidden {
        display: none;
      }
    </style>

    <script async>
      function imagePreview(defaultImageUrl = '') {
        return {
          imageUrl: defaultImageUrl,
          defaultImageUrl: defaultImageUrl,

          previewFile(event) {
            const file = event.target.files[0];
            if (!file || !file.type.includes('image')) {
              this.imageUrl = '';
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
          },
          resetToDefault() {
            this.imageUrl = this.defaultImageUrl;
            document.getElementById('image-input').value = '';
          }
        }
      }
    </script>
  @endpush
</x-layout>
