@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
    <main>
        <x-pages.home.banner :banners="$banners" />
        <x-pages.home.category :categories="$categories" />
        <x-pages.home.product :products="$products" type="slider" name="Produk terlaris" />
        <x-pages.home.product :products="$products" name="Produk terbaru" />
    </main>
@endsection
