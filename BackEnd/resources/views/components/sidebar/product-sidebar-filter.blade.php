@props(['categories', 'brands', 'ratingCounts'])
@push('styles')
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px; /* Độ rộng rất mảnh */
        height: 4px; /* Độ cao (nếu scroll ngang) */
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e5e7eb; /* Màu xám nhạt (gray-200) */
        border-radius: 10px;       /* Bo tròn mềm mại */
        transition: background-color 0.3s;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #000;    /* Chuyển sang màu đen */
    }

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
    }
    
    .custom-scrollbar:hover {
        scrollbar-color: #9ca3af transparent;
    }
</style>
@endpush

<form method="GET" action="{{ route('user.product.index') }}">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <aside class="w-72 hidden lg:block border-r border-gray-100 p-8 sticky top-[73px] h-[calc(100vh-73px)] overflow-y-auto custom-scrollbar pt-6">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-lg font-bold font-serif">Bộ lọc</h3> {{-- Thêm font-serif nếu muốn sang trọng --}}
            @if(request()->hasAny(['category', 'brand', 'price_from', 'price_to', 'rating']))
                <a href="{{ route('user.product.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-gray-400 border-b border-gray-300 hover:text-black hover:border-black transition pb-0.5">
                    Xóa lọc
                </a>
            @endif
        </div>

        {{-- Categories --}}
        <div class="mb-8">
            <h4 class="text-xs font-bold uppercase tracking-widest mb-4 text-gray-500">Danh mục</h4>
            <ul class="space-y-1 text-sm">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}"
                            class="block py-1.5 pl-2 border-l-2 transition-all duration-300 
                            {{ request('category') == $category->id 
                                ? 'border-black font-bold text-black bg-gray-50' 
                                : 'border-transparent text-gray-500 hover:text-black hover:pl-3' }}"
                        >
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Brands --}}
        <div class="mb-8 border-t border-gray-100 pt-6">
            <h4 class="text-xs font-bold uppercase tracking-widest mb-4 text-gray-500">Thương hiệu</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($brands as $brand)
                    <label class="cursor-pointer relative">
                        <input type="radio" 
                            name="brand" 
                            value="{{ $brand->id }}" 
                            {{ request('brand') == $brand->id ? 'checked' : '' }}
                            class="peer sr-only"
                            onchange="this.form.submit()" {{-- Tự động submit khi chọn --}}
                        >
                        <div class="px-3 py-2 border border-gray-200 text-xs text-gray-500 transition-all duration-200
                                    hover:border-gray-400 hover:text-black
                                    peer-checked:bg-black peer-checked:text-white peer-checked:border-black shadow-sm">
                            {{ $brand->name }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Price --}}
        <div class="mb-8 border-t border-gray-100 pt-6">
            <details open class="group">
                <summary class="flex justify-between items-center cursor-pointer mb-4 list-none">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Khoảng giá</span>
                    <span class="text-xs transform group-open:rotate-180 transition text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>

                <div class="flex items-center gap-2 mb-4">
                    <input type="number" name="price_from" value="{{ request('price_from') }}" placeholder="0đ"
                        class="w-full border border-gray-200 bg-gray-50 p-2.5 text-xs outline-none focus:border-black focus:bg-white transition"
                    >
                    <span class="text-gray-400">-</span>
                    <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="Max"
                        class="w-full border border-gray-200 bg-gray-50 p-2.5 text-xs outline-none focus:border-black focus:bg-white transition"
                    >
                </div>
            </details>
        </div>

        {{-- Rating --}}
        <div class="mb-8 border-t border-gray-100 pt-6">
            <details open class="group">
                <summary class="flex justify-between items-center cursor-pointer mb-4 list-none">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Đánh giá</span>
                    <span class="text-xs transform group-open:rotate-180 transition text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </summary>

                <div class="space-y-2">
                    @foreach([5,4,3,2,1] as $star)
                        @php
                            $count = $ratingCounts[$star] ?? 0;
                        @endphp

                        <label class="block cursor-pointer group/item">
                            <input type="radio" 
                                name="rating" 
                                value="{{ $star }}" 
                                {{ request('rating') == $star ? 'checked' : '' }}
                                class="peer sr-only"
                                onchange="this.form.submit()"
                            >

                            <div class="flex items-center justify-between p-2 border border-transparent rounded-sm transition-all
                                        group-hover/item:bg-gray-50
                                        peer-checked:border-l-4 peer-checked:border-l-black peer-checked:bg-gray-50">

                                <div class="flex items-center gap-3">
                                    <div class="flex text-yellow-400 text-[10px] gap-0.5">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="fas {{ $i <= $star ? 'fa-star' : 'fa-star text-gray-200' }}"></i>
                                        @endfor
                                    </div>

                                    <span class="text-[10px] font-medium text-gray-400 peer-checked:text-black">
                                        Trở lên
                                    </span>
                                </div>

                                <span class="text-[10px] text-gray-400">
                                    ({{ $count }})
                                </span>
                            </div>
                        </label>
                    @endforeach
                    @if(request('rating'))
                        <a href="{{ request()->fullUrlWithQuery(['rating' => null]) }}" class="text-[10px] text-gray-400 underline hover:text-black">
                            Xóa lọc đánh giá
                        </a>
                    @endif
                </div>
            </details>
        </div>

        <div class="sticky bottom-0 bg-white/95 backdrop-blur py-4 border-t border-gray-100">
            <button type="submit"
                class="w-full bg-black text-white text-xs font-bold uppercase py-3 tracking-widest hover:bg-gray-800 transition duration-300">
                Áp dụng
            </button>
        </div>
    </aside>
</form>

@foreach(request()->except(['price_from','price_to']) as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
@endforeach