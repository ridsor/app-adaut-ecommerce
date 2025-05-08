<x-app-layout :title="$title"> 
  <div class="container">
    <div class="row">
      @foreach ($products as $product)
        <div class="col-4">
          <div class="w-full">
            {{-- <image src="{{ $product->image }}" style="width: 100%; aspect-ratio: 1 / 1;" /> --}}
            <div class="name fs-5 fw-bold mb-3">
              {{ $product->name }}</div>
            <div class="reviews">
              <span>
                {{-- {{ $product->reviews }} --}}
                {{ round($product->reviews_avg_rating, 1) ?? 0 }} - {{ $product->reviews_count }}
              </span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</x-app-layout> 
