<x-app-layout :title="$title"> 
    <section>
        <div class="container py-5">
            <div class="row">
                @foreach ($categories as $category)
                <div class="col-md-3">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center">
                        <img src="{{ $category->icon }}" style="width: 100px; aspect-ratio: 1 / 1;" />
                        <div>{{ $category->name }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
