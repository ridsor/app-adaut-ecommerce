@extends('layouts.admin.app')

@section('content')
    <main>
        <div class="container-xl list-item">
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

            <h4 class="mb-0 mt-5">Alamat Pengiriman</h4>
            <hr class="mt-2 mb-4">
            <form method="POST" action="{{ route('admin.address.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <!-- Account details card-->
                <!-- Form Row-->
                <div class="row gx-3 mb-3" style="row-gap: 1rem">
                    <div class="col-md-6">
                        <label class="small mb-1" for="name">Nama Pengirim</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text"
                            name="name" value="{{ old('name', $address?->name) }}">
                        <div class="invalid-feedback">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1" for="phone_number">Nomor Telepon</label>
                        <input class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                            name="phone_number" type="text" value="{{ old('phone_number', $address?->phone_number) }}" />
                        <div class="invalid-feedback">
                            @error('phone_number')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Form Group (username)-->
                <div class="mb-3">
                    <label class="small mb-1" for="search_destination">Provinsi, Kota, Kecamatan, Kabupaten, Kelurahan, Kode
                        Pos</label>
                    <select id="destination" placeholder="Cari...">
                        @if (old('address_label', $address?->address_label))
                            <option selected>{{ old('address_label', $address?->address_label) }}</option>
                        @endif
                    </select>
                    <input type="hidden" name="province_name" value="{{ old('province_name', $address?->province_name) }}">
                    <input type="hidden" name="city_name" value="{{ old('city_name', $address?->city_name) }}">
                    <input type="hidden" name="district_name"
                        value="{{ old('district_name', $address?->district_name) }}">
                    <input type="hidden" name="subdistrict_name"
                        value="{{ old('subdistrict_name', $address?->subdistrict_name) }}">
                    <input type="hidden" name="zip_code" value="{{ old('zip_code', $address?->zip_code) }}">
                    <input type="hidden" name="destination_id"
                        value="{{ old('destination_id', $address?->destination_id) }}">
                    <input type="hidden" name="address_label"
                        value="{{ old('address_label', $address?->address_label) }}">
                    <div class="invalid-feedback">
                        @error('destination_id')
                            Provinsi, Kota, Kecamatan, Kabupaten, Kelurahan, Kode Pos Wajib disi.
                        @enderror
                    </div>
                </div>
                <!-- Form Group (name)-->
                <div class="mb-3">
                    <label class="small mb-1" for="address">Alamat</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                        type="text" placeholder="Nama Jalan, Gedung, No. Rumah">{{ old('address', $address?->address) }}</textarea>
                    <div class="invalid-feedback">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="mb-1" for="note">Detail Lainnya</label>
                    <input class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                        type="text" value="{{ old('note', $address?->note) }}" placeholder="Blok / Unit No., Patokan" />
                    <div class="invalid-feedback">
                        @error('note')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <!-- Save changes button-->
                <button class="btn btn-primary" type="submit">Simpan</button>

            </form>
        </div>
    </main>
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            padding: 8px 12px;
            border: 1px solid rgb(197, 204, 214);
            border-radius: 6px;
            background-color: #fff;
            box-shadow: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .ts-wrapper.is-invalid .ts-control {
            border: 1px solid #e81500 !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#destination', {
            valueField: 'id',
            labelField: 'label',
            searchField: 'label',
            shouldLoad: function(query) {
                return query.length > 3;
            },
            onFocus: function() {
                this.clear();
            },
            load: function(query, callback) {

                const url = '{{ route('api.address.search-destination') }}?search=' + encodeURIComponent(
                    query);
                window.axios.get(url, {
                    headers: {
                        'Authorization': `Bearer {{ Session::get('token') }}`,
                        'Accept': 'application/json',

                    }
                }).then(res => {
                    callback(res.data.data)
                }).catch(() => callback())

            },
            onChange: function(value) {
                data = this.options[value]

                document.querySelector('input[name="province_name"]').value = data.province_name
                document.querySelector('input[name="city_name"]').value = data.city_name
                document.querySelector('input[name="district_name"]').value = data.district_name
                document.querySelector('input[name="subdistrict_name"]').value = data.subdistrict_name
                document.querySelector('input[name="zip_code"]').value = data.zip_code
                document.querySelector('input[name="address_label"]').value = data.label
                document.querySelector('input[name="destination_id"]').value = data.id
            },
            onInitialize: function() {
                if (@json($errors->has('address_label'))) {
                    this.wrapper.classList.add('is-invalid')
                }
            },
            loadThrottle: 300,
            // custom rendering functions for options and items
            render: {
                option: function(item, escape) {
                    return `<div >
                            <span style="font-size:12px">
                                ${ escape(item.label) }
                                </span>
                                </div>`;
                },
                item: function(item, escape) {
                    return `<div>
									<span>
										${ escape(item.label) }
                                        </span>
                                        </div>`;
                },
                no_results: function() {
                    return '<div class="no-results">Tidak ditemukan data yang cocok</div>';
                },
            },
        });
    </script>
@endpush
