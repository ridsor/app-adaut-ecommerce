@props(['product'])

<a class="product-item text-decoration-none" href="{{ route("product.detail",["slug" => $product->slug]) }}">
    <div class="d-flex flex-column justify-content-center rounded-3 p-4">
        <div
            class="product-image mb-3 image d-flex justify-content-center ratio ratio-1x1 align-items-center bg-card rounded-3 overflow-hidden">
            <img src="https://themewagon.github.io/FoodMart/images/product-thumb-1.png"
                style="background-position: center" class="h-100 object-fit-contain" />
        </div>
        <div class="product-title fw-semibold mb-2">{{ $product->name }}</div>
        <div class="d-flex align-items-center mb-2" style="gap: 8px">
            <div class="product-sold">
                1 Terjual
            </div>
            <div class="d-flex product-rate align-items-center">
                <img src="/icons/rate.svg" alt=""
                    style="width: 13px; height: 13px; transform: translateY(-1px)">
                <span class="fw-bold">5</span>
            </div>
        </div>
        <div x-data class="product-price fw-semibold" x-text="$store.globalState.formattedPrice(15000)">
        </div>
    </div>
</a>
