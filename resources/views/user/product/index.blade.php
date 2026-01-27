@extends('user.layouts.app')
@section('title', 'Danh sách sản phảm')
@section('content')

<div class="max-w-[1440px] pt-12 mx-auto flex min-h-screen bg-white">
    <!-- Filter -->
    <x-sidebar.product-sidebar-filter 
        :categories="$categories" 
        :brands="$brands"
        :ratingCounts="$ratingCounts"
    />

    <main class="flex-1 p-5 lg:p-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 pb-4 border-b border-gray-100">
            <div>
                <span class="text-xs text-gray-400 uppercase tracking-widest">
                    Kết quả tìm kiếm cho
                </span>

                <h1 class="text-3xl mt-1 italic">
                    "{{ request('search') ?? 'Tất cả sản phẩm' }}"
                    <span class="text-lg not-italic text-gray-400">
                        ({{ $products->total() }} kết quả)
                    </span>
                </h1>
            </div>

            <div class="flex gap-4 mt-4 md:mt-0 w-full md:w-auto">
                <form method="GET" class="relative group flex-1 md:flex-none">
                    @foreach (request()->except('sort', 'page') as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <select name="sort" onchange="this.form.submit()"
                        class="appearance-none w-full md:w-48 bg-transparent border-b border-gray-300 py-2 pr-8 text-sm focus:outline-none cursor-pointer"
                    >
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                            Mới nhất
                        </option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Giá: Thấp đến Cao
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Giá: Cao đến Thấp
                        </option>
                        <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>
                            Bán chạy nhất
                        </option>
                    </select>

                    <i class="fas fa-chevron-down absolute right-0 top-3 text-xs text-gray-400 pointer-events-none"></i>
                </form>
            </div>
        </div>

        <!-- Product List -->
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
            @foreach ($products as $product)
            @php
                $isNew = $product->created_at->diffInDays(now()) < 30;
                $firstVariant = $product->variants->first();
                $imageUrl = str_starts_with($product->thumbnail, 'http') 
                            ? $product->thumbnail 
                            : asset('storage/' . $product->thumbnail);
                $isWishlisted = false;
                if(auth()->check()) {
                    $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                }
            @endphp
                <div class="group cursor-pointer shadow px-2">
                    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-100">
                        <a href="{{ route('user.product.show', $product->slug) }}" class="block w-full h-full">
                            <img
                                src="{{ $imageUrl }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >
                        </a>

                        {{-- BUTTON (Tim - Giỏ hàng - Xem nhanh) --}}
                        <div class="absolute bottom-4 right-4 flex flex-col gap-2 translate-x-12 group-hover:translate-x-0 transition-transform duration-300">
                            {{-- 1. NÚT TIM (WISHLIST) --}}
                            <button type="button"
                                onclick="toggleWishlistGlobal(this, {{ $product->id }})"
                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow transition-all duration-300
                                       {{ $isWishlisted ? 'text-red-500' : 'text-gray-900 hover:bg-black hover:text-white' }}"
                                title="{{ $isWishlisted ? 'Bỏ thích' : 'Yêu thích' }}"
                            >
                                <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart text-sm"></i>
                            </button>
                            
                            {{-- 2. ADD TO CART BUTTON --}}
                            @if($firstVariant && $firstVariant->quantity >= 0)
                                <form action="{{ route('user.cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="variant_id" value="{{ $firstVariant->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    
                                    <button type="submit"
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow hover:bg-black hover:text-white transition" title="Thêm vào giỏ"
                                    >
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-10 h-10 bg-gray/90 rounded-full flex items-center justify-center shadow hover:bg-black hover:text-white transition tracking-widest cursor-not-allowed font-bold backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-x-icon lucide-package-x"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/><path d="m17 13 5 5m-5 0 5-5"/></svg>
                                </button>
                            @endif

                            {{-- 3. VIEW DETAIL--}}
                            <a href="{{ route('user.product.show', $product->slug) }}"
                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow hover:bg-black hover:text-white transition"
                                title="Xem nhanh"
                            >
                                <i class="far fa-eye text-sm"></i>
                            </a>
                        </div>

                        {{-- BADGES --}}
                        <x-badges.badges :product="$product" />
                    </div>

                    {{-- Info card --}}
                    <div>
                        <a href="{{ route('user.product.show',$product) }}">
                            <h4 class=" text-lg group-hover:underline decoration-1 underline-offset-4 truncate">{{ $product->name }}</h4>
                            <div class="flex justify-between items-center mt-1">
                                @if ($product->sale_price > 0)
                                <p class="text-red-600 text-sm">
                                    <span class="text-gray-500 text-sm line-through mr-1">
                                        <x-money :value="$product->price" />
                                    </span>
                                    <x-money :value="$product->sale_price" />
                                </p>
                                @else
                                <p class="text-gray-500 text-sm">
                                    <x-money :value="$product->price" />
                                </p>
                                @endif
                            </div>
                        </a>
                        <div class="flex-space-x-1">
                            <span class="w-3 h-3 rounded-full bg-black border border-white"></span>
                            <span class="w-3 h-3 rounded-full bg-blue-900 border border-white"></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-20 flex justify-center">
            <nav class="flex gap-2">
                {{ $products->links() }}
            </nav>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif
</script>
@endpush
