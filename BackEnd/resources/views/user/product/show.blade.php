@extends('user.layouts.app')
@section('title', $product->name)
@php
    $sold_quantity = $product->variants->flatMap(fn ($variant) => $variant->orderDetails)->sum('quantity')
@endphp

@section('content')

<div class="pt-20 lg:pt-28 max-w-7xl mx-auto px-6">
    {{-- Chi tiết sản phẩm --}}
    <div class="flex flex-col lg:flex-row lg:min-h-screen">
        {{-- Ảnh --}}
        <div class="w-full lg:w-3/5 relative bg-gray-50">
            {{-- Ảnh chính --}}
            <img id="main-image"
                src="{{ asset('storage/' . $product->thumbnail) }}"
                class="w-full h-[500px] lg:h-full object-cover"
                alt="{{ $product->name }}"
            >

            {{-- Gallery thumbnails --}}
            @if($product->gallery && count($product->gallery) > 0)
            <div class="absolute bottom-6 left-6 right-6">
                <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                    {{-- Thêm ảnh thumbnail chính vào list luôn --}}
                    <img onclick="changeImage(this)"
                        src="{{ asset('storage/' . $product->thumbnail) }}"
                        class="gallery-thumb w-20 h-24 object-cover border-2 border-black cursor-pointer bg-white"
                    >

                    @foreach($product->gallery as $img)
                        <img onclick="changeImage(this)"
                            src="{{ asset('storage/' . $img) }}"
                            class="gallery-thumb w-20 h-24 object-cover border-2 border-white/50 hover:border-black cursor-pointer bg-white"
                        >
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        {{-- Thông tin sản phẩm --}}
        <div class="w-full lg:w-2/5 bg-white px-6 py-8 lg:px-12 lg:py-12">
            <div class="max-w-md mx-auto sticky top-24">
                <span class="text-sm text-gray-400 tracking-widest uppercase block mb-2">
                    {{ $product->category->name ?? 'Collection' }}
                </span>
                <h1 class="text-3xl md:text-4xl mb-2 text-gray-900">
                    {{ $product->name }}
                </h1>
                <p class="text-xs text-gray-500 mb-2 flex items-center gap-4">
                    <span>Kho: {{ $product->variants->sum('quantity') }}</span>
                    <span>Lượt xem: {{ $product->view }}</span>
                    <span>Lượt Yêu Thích: {{ $product->wishlist_count }}</span>
                    <span>Đã bán: {{ $sold_quantity }}</span>
                </p>
                    
                <div class="flex items-end gap-4 mb-6">
                    <p class="text-2xl font-light text-black">
                        {{ number_format($product->price) }} ₫
                    </p>
                    @if($product->variants->sum('quantity') > 0)
                        <span id="stock-status-text" class="text-sm text-green-600 mb-1">(Còn hàng)</span>
                    @else
                        <span id="stock-status-text" class="text-sm text-red-600 mb-1">(Hết hàng)</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-8 border-b border-gray-100 pb-8">
                    {{ $product->description }}
                </p>

                <form action="{{ route('user.cart.store') }}" method="POST" id="add-to-cart-form">
                    @php
                        $defaultVariant = $product->variants->where('quantity', '>', 0)->first() ?? $product->variants->first();
                        $isWishlisted = false;
                        if(auth()->check()) {
                            $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                        }
                    @endphp
                    @csrf
                    <input type="hidden" name="variant_id" id="selected-variant-id" required>
                    
                    {{-- PHẦN MÀU SẮC --}}
                    @if($colors->isNotEmpty()) 
                        <div class="mb-6">
                            <label class="font-bold text-xs uppercase mb-3 block">
                                Màu sắc: <span id="selected-color-text" class="font-normal text-gray-500">Chọn màu</span>
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($colors as $color)
                                    <label class="cursor-pointer select-none group">
                                        <input type="radio" name="color_opt" value="{{ $color->id }}" 
                                            data-name="{{ $color->name }}" 
                                            class="peer sr-only"
                                            {{ ($defaultVariant && $defaultVariant->color_id == $color->id) ? 'checked' : '' }} 
                                        >
                                        
                                        <div class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center
                                            transition-all duration-200 hover:scale-110
                                            peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-black peer-checked:border-transparent"
                                            style="background: {{ $color->hex_code }};">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- PHẦN KÍCH CỠ --}}
                    @if($sizes->isNotEmpty())
                        <div class="mb-8">
                            <label class="font-bold text-xs uppercase mb-3 block">
                                Kích cỡ: <span id="selected-size-text" class="font-normal text-gray-500">Chọn size</span>
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($sizes as $size)
                                    <label class="cursor-pointer select-none group">
                                        <input type="radio" name="size_opt" value="{{ $size->id }}" 
                                            data-name="{{ $size->name }}"
                                            class="peer sr-only" 
                                            onchange="updateVariantState()"
                                            {{ ($defaultVariant && $defaultVariant->size_id == $size->id) ? 'checked' : '' }}
                                        >
                                        <div class="min-w-[40px] px-3 h-10 flex items-center justify-center border border-gray-200 rounded-sm text-sm font-bold bg-white text-gray-900
                                                    peer-checked:bg-black peer-checked:text-white peer-checked:border-black
                                                    hover:border-black transition-all">
                                            {{ $size->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-4">
                        {{-- Chọn số lượng --}}
                        <div class="flex border border-gray-300 w-32 items-center h-12">
                            <button type="button" class="btn-dec w-10 h-full hover:bg-gray-100 flex items-center justify-center text-gray-600">-</button>
                            <input type="text" name="quantity" value="1" min="1" class="w-12 text-center border-none text-sm bg-transparent p-0 focus:ring-0 h-full text-gray-900 font-medium appearance-none m-0"/>
                            <button type="button" class="btn-inc w-10 h-full hover:bg-gray-100 flex items-center justify-center text-gray-600">+</button>
                        </div>

                        {{-- Nút Thêm Giỏ --}}
                        <button type="submit" 
                            class="flex-1 bg-gray-900 text-white uppercase tracking-widest text-xs font-bold hover:bg-black transition h-12"
                        >
                            Thêm vào giỏ
                        </button>

                        {{-- Nút Yêu thích --}}
                        <form action="{{ route('user.wishlist.toggle') }}" method="POST">
                            @csrf
                            
                            <button type="button" 
                                id="btn-wishlist"
                                data-product-id="{{ $product->id }}"
                                data-url="{{ route('user.wishlist.toggle') }}"
                                class="w-12 h-12 border flex items-center justify-center transition group
                                {{ $isWishlisted ? 'border-red-500 text-red-700' : 'border-gray-300 text-gray-600 hover:text-red-500 hover:border-red-500' }}"
                            >
                                <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart text-lg transition-transform group-active:scale-75"></i>
                            </button>
                        </form>
                    </div>
                    
                    <p id="variant-error" class="text-red-500 text-xs mt-2 hidden"></p>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 space-y-4">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fa-solid fa-truck-fast w-5"></i>
                        <span>Miễn phí vận chuyển đơn từ 500k</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fa-solid fa-rotate-left w-5"></i>
                        <span>Đổi trả miễn phí trong 30 ngày</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gợi ý sản phẩm liên quan --}}
    <div class="py-16 border-t border-gray-100">
        <h3 class=" text-2xl mb-8 text-center">Các sản phẩm liên quan </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @foreach($relatedProducts as $relatedProduct)
                <x-products.product-card :product="$relatedProduct" />
            @endforeach
        </div>
    </div>

    {{-- Đánh giá sản phẩm --}}
    <div class="py-16 border-t border-gray-100 mt-16" id="reviews-section">
        <h3 class=" text-2xl mb-10">Đánh giá khách hàng</h3>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- THỐNG KÊ RATING --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-gray-50 p-8 text-center">
                    <div class="text-5xl  font-bold text-gray-900 mb-2">{{ $product->rating }}</div>
                    <div class="flex justify-center gap-1 text-yellow-500 text-sm mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="fas fa-star"></i>
                            @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500">Dựa trên {{ $totalRating }} đánh giá</p>
                </div>

                {{-- Progress Bars --}}
                <div class="space-y-3">
                    @foreach($ratingDist as $star => $count)
                        @php
                            $percent = $totalRating > 0 ? ($count / $totalRating) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3 text-sm">
                            <span class="w-3 font-bold">{{ $star }}</span>
                            <i class="fas fa-star text-xs text-gray-300"></i>
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-900" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="w-8 text-right text-gray-400 text-xs">
                                {{ $count }}
                            </span>
                        </div>
                    @endforeach
                </div>
                
                <button x-data @click="$dispatch('open-review-modal')" 
                        class="w-full border border-gray-900 text-gray-900 py-3 text-sm font-bold uppercase tracking-wider hover:bg-black hover:text-white transition">
                    Viết đánh giá
                </button>   
                {{-- Popup Review Modal --}}
                <x-review-modal :product="$product" />
            </div>

            {{-- DANH SÁCH REVIEW --}}
            <div class="lg:col-span-8">
                <div class="space-y-8 pt-4">
                    @forelse($reviews as $review)
                        <x-cardReview.review-card :review="$review" />
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500">Chưa có đánh giá nào cho sản phẩm này.</p>
                            <p class="text-sm text-gray-400 mt-2">Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                        </div>
                    @endforelse

                    {{-- Pagination (Review) --}}
                    <div class="pt-4 flex justify-center">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const variants = @json($product->variants);

    // 1. Logic đổi ảnh
    function changeImage(element) {
        document.getElementById('main-image').src = element.src;
        document.querySelectorAll('.gallery-thumb').forEach(el => {
            el.classList.remove('border-black');
            el.classList.add('border-white/50');
        });
        element.classList.remove('border-white/50');
        element.classList.add('border-black');
    }

    // 2. Logic Update Variant ID
    function updateVariantState() {
        const colorInput = document.querySelector('input[name="color_opt"]:checked');
        const sizeInput = document.querySelector('input[name="size_opt"]:checked');

        const selectedColorId = colorInput ? Number(colorInput.value) : null;
        const selectedSizeId = sizeInput ? Number(sizeInput.value) : null;

        const hiddenInput = document.getElementById('selected-variant-id');
        hiddenInput.value = ""; 

        const stockStatusText = document.getElementById('stock-status-text');

        // Update text color & size label
        if(colorInput) document.getElementById('selected-color-text').innerText = colorInput.dataset.name;
        if(sizeInput) document.getElementById('selected-size-text').innerText = sizeInput.dataset.name;

        if (selectedColorId && selectedSizeId) {
            const match = variants.find(v => v.color_id === selectedColorId && v.size_id === selectedSizeId);

            if (match) {
                if (match.quantity > 0) {
                    stockStatusText.innerText = '(Còn hàng)';
                    stockStatusText.className = 'text-sm text-green-600 mb-1';
                    hiddenInput.value = match.id;
                } else {
                    stockStatusText.innerText = '(Hết hàng)';
                    stockStatusText.className = 'text-sm text-red-600 mb-1';
                }
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        updateVariantState();
    });

    // 3. Tăng giảm số lượng
    document.querySelector('.btn-inc').addEventListener('click', function() {
        let input = this.previousElementSibling;
        input.value = parseInt(input.value) + 1;
    });

    document.querySelector('.btn-dec').addEventListener('click', function() {
        let input = this.nextElementSibling;
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    });

    // 5. Thông báo Session (Thành công/Thất bại từ Controller)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    // 6. XỬ LÝ WISHLIST
    const btnWishlist = document.getElementById('btn-wishlist');
    if(btnWishlist) {
        btnWishlist.addEventListener('click', function() {
            @guest
                Swal.fire({
                    icon: 'info',
                    title: 'Yêu cầu đăng nhập',
                    text: 'Bạn cần đăng nhập để lưu sản phẩm yêu thích',
                    confirmButtonText: 'Đăng nhập ngay',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
                return;
            @endguest

            const btn = this;
            const icon = btn.querySelector('i');
            const url = btn.dataset.url;
            const productId = btn.dataset.productId;
            
            const variantId = document.getElementById('selected-variant-id').value;

            const isActive = btn.classList.contains('text-red-500');
            
            if (isActive) {
                // Đang like -> Bỏ like (Về màu xám, tim rỗng)
                btn.classList.remove('border-red-500', 'text-red-500');
                btn.classList.add('border-gray-300', 'text-gray-600');
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // Chưa like -> Like (Lên màu đỏ, tim đặc)
                btn.classList.remove('border-gray-300', 'text-gray-600');
                btn.classList.add('border-red-500', 'text-red-500');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }

            // --- GỬI REQUEST ---
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    variant_id: variantId || null 
                })
            })
            .then(response => response.json())
            .then(data => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',    
                    title: data.message
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi', 'Không thể cập nhật danh sách yêu thích', 'error');
            });
        });
    }
</script>
@endpush