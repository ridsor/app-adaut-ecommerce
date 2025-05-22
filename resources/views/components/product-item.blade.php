@props(["product"])

<a x-data class="product-item text-decoration-none" href="{{ route("product.detail", ["slug" => $product->slug]) }}">
  <div class="d-flex flex-column justify-content-center rounded-3">
    <div class="position-relative">
      <div class="{{ $product->stock > 0 ? "d-none" : "" }}">
        <div
          class="isEmpity position-absolute top-0 start-0 end-0 bottom-0 d-flex justify-content-center align-items-center">
          <div class="rounded-circle p-5 d-flex justify-content-center align-items-center"
            style="background-color: rgba(0,0,0,.5); aspect-ratio: 1/1; z-index: 2">
            <span class="text-white">Habis</span>
          </div>
        </div>
      </div>
      <div
        class="product-image mb-3 image d-flex justify-content-center ratio ratio-1x1 align-items-center bg-card rounded-3 overflow-hidden">
        <img src="{{ $product->image }}" style="background-position: center" class="object-fit-contain" />
      </div>
    </div>
    <div class="product-title fw-semibold mb-2">{{ $product->name }}</div>
    <div class="d-flex align-items-center mb-2" style="gap: 8px">
      <div class="product-sold">
        <span x-text="$store.globalState.formatNumberShort({{ $product->total_sold ?? 0 }})">0</span> Terjual
      </div>
      <div class="d-flex product-rate align-items-center gap-1">
        <img src="/icons/rate.svg" alt="" style="width: 13px; height: 13px; transform: translateY(-0.5px)">
        <span class="fw-bold">{{ round($product->reviews_avg_rating, 1) ?? 0 }}</span>
        <span>(<span x-text="$store.globalState.formatNumberShort({{ $product->review_count ?? 0 }})">0</span>)</span>
      </div>
    </div>
    <div x-data class="product-price fw-semibold" x-text="$store.globalState.formattedPrice({{ $product->price }})">
      Rp 0
    </div>
  </div>
</a>
