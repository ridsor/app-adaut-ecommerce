@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .cart-input {
            background: #f8f8f8;
            border: .5px solid #e2e3e5
        }

        .cart-input .plus,
        .cart-input .minus {
            background: #e2e3e5;
        }

        .cart-input .plus:hover,
        .cart-input .minus:hover {
            background: #f2f3f5;
        }

        .cart-input input {
            outline: none
        }

        .category-item {
            transition: all 500ms;
            box-shadow: 0px 5px 22px rgba(0, 0, 0, 0.04)
        }

        .category-item:hover {
            transform: translateY(-1rem)
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
    <main>
        <x-pages.home.banner :banners="$banners" />
        <x-pages.home.service />
        <x-pages.home.category :categories="$categories" />
        <x-pages.home.product :products="$products" type="slider" name="Produk terlaris" />
        <x-pages.home.product :products="$products" name="Produk terbaru" />
    </main>
@endsection
