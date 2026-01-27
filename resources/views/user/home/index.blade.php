@extends('user.layouts.app')
@section('title', 'Trang chủ')

@section('content')

<x-slideshow.slideshow :slides="$slides" />

<!-- Content -->
<main class="max-w-7xl mx-auto">
    <!-- Sản phẩm mới -->
    <section class="px-6 py-12 mt-6">
        <!-- Title -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-4">
            <h3 class="text-3xl font-bold">Sản phẩm mới</h3>

            <div class="flex space-x-4 mt-4 md:mt-0 text-sm">
                <a href="{{ route('user.product.index') }}" class="hover:underline">View All</a>
            </div>
        </div>
        <!-- Product List -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
            <!-- Product Card -->
            @foreach($newProducts as $product)
                <x-products.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- Sản Phẩm Nổi Bật -->
    <section class="px-6 py-12">
        <!-- Title -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-4">
            <h3 class="text-3xl font-bold">Sản Phẩm Nổi Bật</h3>

            <div class="flex space-x-4 mt-4 md:mt-0 text-sm">
                <a href="{{ route('user.product.index') }}" class="hover:underline">View All </a>
            </div>
        </div>
        <!-- Product List -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
            <!-- Product Card -->
            @foreach($featuredProducts as $product)
                <x-products.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- Bộ sưu tập -->
    <section class="px-6 py-12">
        <!-- Title -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-4">
            <h3 class="text-3xl font-bold">Bộ sưu tập hot</h3>

            <div class="flex space-x-4 mt-4 md:mt-0 text-sm">
                <a href="{{ route('user.product.index') }}" class="hover:underline">View All </a>
            </div>
        </div>
        <!-- Product List -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
            <!-- Product Card -->
            @foreach($collectionProducts as $product)
                <x-products.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- Best seller -->
    <section class="px-6 py-12">
        <!-- Title -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-4">
            <h3 class="text-3xl font-bold">Sản phẩm bán chạy</h3>

            <div class="flex space-x-4 mt-4 md:mt-0 text-sm">
                <a href="{{ route('user.product.index') }}" class="hover:underline">View All </a>
            </div>
        </div>
        <!-- Product List -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
            <!-- Product Card -->
            @foreach($bestSellerProducts as $product)
                <x-products.product-card :product="$product" />
            @endforeach
        </div>
    </section>
</main>
@endsection