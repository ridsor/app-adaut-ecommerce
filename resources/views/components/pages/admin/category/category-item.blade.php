@props(['category'])

<div class="item card card-icon lift lift-sm mb-3 position-static overflow-hidden" style="cursor: pointer" role="link"
    tabindex="0" aria-label="category item">
    <div class="row g-2 flex-column flex-md-row">
        <div class="col-auto card-icon-aside p-3" style="background: #f9f9f9">
            <div class="ratio ratio-1x1  overflow-hidden" style="width: 112px">
                <div class="wrapper align-items-center d-flex justify-content-center">
                    <img src="{{ asset('storage/' . $category->icon) }}" style="background-position: center"
                        class="h-100 object-fit-contain" />
                </div>
            </div>
        </div>

        <div class="col w-100 d-flex align-items-center">
            <div class="row p-2 p-md-3 flex-column flex-md-row flex-grow-1 g-1">
                <div class="col">
                    <div class="text-primary fw-semibold">
                        {{ $category->name }}
                    </div>
                </div>
                <div class="col text-dark fw-semibold d-flex align-items-center gap-1">
                    <span>
                        <i data-feather="shopping-bag" style="transform: translateY(.2rem)"></i>
                    </span>
                    <span>
                        {{ $category->products_count }}
                    </span>
                    <span>
                        Produk
                    </span>
                </div>
            </div>
        </div>
        <div
            class="col-12 col-md-auto d-flex align-items-center justify-content-end justify-content-md-center gap-1 flex-md-column p-2">
            <a href="{{ route('category.edit', ['kategori' => $category->slug]) }}" class="btn btn-warning btn-icon"
                @click.stop>
                <i data-feather="edit"></i>
            </a>
            <button class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#confirmModal"
                @click.stop="deleteRoute = '{{ route('category.destroy', ['kategori' => $category->slug]) }}'"
                type="button">
                <i data-feather="trash"></i>
            </button>
        </div>
    </div>
</div>
