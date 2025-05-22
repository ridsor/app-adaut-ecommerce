@props(["product"])

<div class="item card card-icon lift lift-sm mb-3 position-static overflow-hidden" style="cursor: pointer" role="link"
  tabindex="0" aria-label="product item"
  @click="window.location.href='{{ route("product.show", ["produk" => $product->slug]) }}'"
  @keydown.enter="window.location.href='{{ route("product.show", ["produk" => $product->slug]) }}'"
  @keydown.space.prevent="window.location.href='{{ route("product.show", ["produk" => $product->slug]) }}'">
  <div class="row g-2 flex-column flex-md-row">
    <div class="col-auto card-icon-aside p-0" style="background: #f9f9f9">
      <div class="position-relative">
        <div class="{{ $product->stock > 0 ? "d-none" : "" }}">
          <div class="top-0 start-0 end-0 bottom-0 z-2 d-flex justify-content-center align-items-center"
            style="position: absolute !important">
            <div class="d-flex justify-content-center align-items-center w-100 h-100"
              style="background-color: rgba(0,0,0,.5); aspect-ratio: 1/1;">
              <span class="text-white fs-6">Habis</span>
            </div>
          </div>
        </div>
        <div class="ratio ratio-1x1  overflow-hidden" style="width: 112px">
          <div class="wrapper align-items-center d-flex justify-content-center">
            <img src="{{ $product->image }}" style="background-position: center" class="h-100 object-fit-contain" />
          </div>
        </div>
      </div>
    </div>

    <div class="col w-100 d-flex align-items-center">
      <div class="row p-2 p-md-3 flex-column flex-md-row flex-grow-1 g-1">
        <div class="col">
          <div class="text-dark fw-semibold">
            {{ $product->name }}
          </div>
          <div class="text-primary fw-semibold">
            <span x-text="$store.globalState.formattedPrice({{ $product->price }})">Rp
              0</span>
          </div>
        </div>
        <div class="col text-dark fw-semibold d-flex align-items-center gap-1">
          <div style="width: 40px">
            <div class="d-flex justify-content-center ratio ratio-1x1 align-items-center">
              <img src="{{ $product->category->icon }}" style="background-position: center; object-fit: contain" />
            </div>
          </div>
          <span>
            {{ $product->category->name }}
          </span>
        </div>
      </div>
    </div>
    <div
      class="col-12 col-md-auto d-flex align-items-center justify-content-end justify-content-md-center gap-1 flex-md-column p-2">
      <a href="{{ route("product.edit", ["produk" => $product->slug]) }}" class="btn btn-warning btn-icon" @click.stop>
        <i data-feather="edit"></i>
      </a>
      <button class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#confirmModal"
        @click.stop="deleteRoute = '{{ route("product.destroy", ["produk" => $product->slug]) }}'" type="button">
        <i data-feather="trash"></i>
      </button>
    </div>
  </div>
</div>
