<div class="hidden lg:flex items-center justify-center space-x-10 text-xs uppercase tracking-widest font-semibold text-black border-t border-gray-400/10 p-2 shadow">
    @foreach ($categories as $category)
        <div class="relative group">
            {{-- Category --}}
            <button class="hover:text-black transition flex items-center gap-1">
                {{ $category->name }}
                @if ($category->brands->count())
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                @endif
            </button>

            {{-- Dropdown Brand --}}
            @if ($category->brands->count())
                <div class="absolute left-0 top-full mt-4 w-40 bg-white shadow-xl rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">

                    @foreach ($category->brands as $brand)
                        <a href="{{ route('user.product.index', [
                            'category' => $category->slug,
                            'brand'    => $brand->slug
                        ]) }}"
                        class="block px-5 py-3 text-xs text-black hover:bg-gray-100 transition">
                            {{ $brand->name }}
                        </a>
                    @endforeach

                </div>
            @endif
        </div>
    @endforeach

    {{-- Sale --}}
    <a href="{{ route('user.product.index', ['sale' => 1]) }}"
       class="text-red-500 hover:text-red-600 transition relative group">
        Sale
    </a>
</div>
