@props(['product'])

@php
    $firstVariant = $product->variants->first();
    $imageUrl = str_starts_with($product->thumbnail, 'http') 
                ? $product->thumbnail 
                : asset('storage/' . $product->thumbnail);
    $isWishlisted = false;
    if(auth()->check()) {
        $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
    }
@endphp

<div class="group relative product-card-item" data-id="{{ $product->id }}">
    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-100 w-full rounded-lg">
        {{-- IMAGE --}}
        <a href="{{ route('user.product.show', $product->slug) }}" class="block w-full h-full">
            <img
                src="{{ $imageUrl }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                alt="{{ $product->name }}"
                loading="lazy"
            >
        </a>

        {{-- WISHLIST BUTTON --}}
        <button type="button"
            onclick="toggleWishlistGlobal(this, {{ $product->id }})"
            class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 shadow-sm
                   {{ $isWishlisted ? 'bg-red-50 text-red-500' : 'bg-white/80 text-gray-400 hover:bg-red-50 hover:text-red-500' }}
                   backdrop-blur-[2px] hover:scale-110 active:scale-95"
            title="{{ $isWishlisted ? 'Bỏ thích' : 'Yêu thích' }}"
        >
            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart text-lg transition-transform duration-300"></i>
        </button>

        {{-- ADD TO CART BUTTON --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300 z-10">
            @if($firstVariant && $firstVariant->quantity > 0)
                <form action="{{ route('user.cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="variant_id" value="{{ $firstVariant->id }}">
                    <input type="hidden" name="quantity" value="1">
                    
                    <button type="submit" class="w-full bg-black text-white font-bold py-3 text-xs uppercase tracking-widest hover:bg-gray-800 hover:text-white transition-colors shadow-lg backdrop-blur-sm">
                        Thêm vào giỏ
                    </button>
                </form>
            @else
                <button disabled class="w-full bg-gray-200/90 text-gray-500 py-3 text-xs uppercase tracking-widest cursor-not-allowed font-bold backdrop-blur-sm">
                    Hết hàng
                </button>
            @endif
        </div>

        {{-- BADGES --}}
        <div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
            @if(isset($product->view) && $product->view >= 500)
                <span class="bg-black text-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider">Best Seller</span>
            @endif
        </div>
    </div>

    {{-- INFO --}}
    <div class="text-center">
        <a href="{{ route('user.product.show', $product->slug) }}">
            <h4 class="text-base text-gray-900 group-hover:underline transition-colors line-clamp-1 px-2">
                {{ $product->name }}
            </h4>
            <div class="flex justify-center items-center gap-2 mt-1">
                @if ($product->sale_price > 0)
                    <p class="text-red-600 text-sm">
                        <span class="text-gray-500 text-sm line-through mr-1">
                            <x-money :value="$product->price" />
                        </span>
                        <x-money :value="$product->sale_price" />
                    </p>
                @else
                    <p class="text-gray-500 text-sm text-center">
                        <x-money :value="$product->price" />
                    </p>
                @endif
            </div>
        </a>
        
        {{-- Color Dots --}}
        @if($product->variants->isNotEmpty())
            <div class="flex justify-center items-center gap-1.5 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 h-4">
                @foreach($product->variants->unique('color')->take(4) as $variant)
                    @php
                        $colorDisplay = $variant->color->value ?? $variant->color; 
                        $isWhite = in_array(strtolower($colorDisplay), ['#ffffff', 'white', 'trắng']);
                    @endphp
                    <span class="w-3 h-3 rounded-full border {{ $isWhite ? 'border-gray-300' : 'border-transparent' }} shadow-sm" 
                          style="background-color: {{ $colorDisplay }};"
                          title="{{ $variant->color }}">
                    </span>
                @endforeach
            </div>
        @else
            <div class="h-4 mt-2"></div>
        @endif
    </div>
</div>