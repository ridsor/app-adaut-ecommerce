@props(["banner"])

<div class="item card card-icon lift lift-sm mb-3 position-static overflow-hidden" style="cursor: pointer" role="link"
  tabindex="0" aria-label="banner item"
  @click="window.location.href='{{ route("banner.show", ["spanduk" => $banner->id]) }}'"
  @keydown.enter="window.location.href='{{ route("banner.show", ["spanduk" => $banner->id]) }}'"
  @keydown.space.prevent="window.location.href='{{ route("banner.show", ["spanduk" => $banner->id]) }}'">
  <div class="row g-2 flex-column flex-md-row">
    <div class="col-auto card-icon-aside p-0" style="background: #f9f9f9">
      <div class="ratio ratio-1x1  overflow-hidden" style="width: 112px">
        <div class="wrapper align-items-center d-flex justify-content-center">
          <img src="{{ asset("storage/" . $banner->image) }}" style="background-position: center" alt=""
            class="h-100 object-fit-contain" />
        </div>
      </div>
    </div>

    <div class="col w-100 d-flex align-items-center">
      <div class="row p-1 flex-column flex-md-row flex-grow-1">
        <div class="col">
          <div class="text-primary fw-semibold">
            <span
              style="-webkit-line-clamp: 1;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden; max-height: 25px">
              {{ $banner->title }}
            </span>
          </div>
          <div class="text-dark">
            <span
              style="-webkit-line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box; text-overflow: ellipsis; overflow: hidden; max-height: 50px">
              {{ $banner->description }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div
      class="col-12 col-md-auto d-flex align-items-center justify-content-end justify-content-md-center gap-1 flex-md-column p-2">
      <a href="{{ route("banner.edit", ["spanduk" => $banner->id]) }}" class="btn btn-warning btn-icon" @click.stop>
        <i data-feather="edit"></i>
      </a>
      <button class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#confirmModal"
        @click.stop="deleteRoute = '{{ route("banner.destroy", ["spanduk" => $banner->id]) }}'" type="button">
        <i data-feather="trash"></i>
      </button>
    </div>
  </div>
</div>
