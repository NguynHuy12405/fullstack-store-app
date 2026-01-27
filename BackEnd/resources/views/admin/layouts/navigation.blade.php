<style>
    nav.sidebar-nav::-webkit-scrollbar {
        width: 8px;
    }

    nav.sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    nav.sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    nav.sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    nav.sidebar-nav {
        scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        scrollbar-width: thin;
    }
</style>

<nav class="sidebar-nav font-bold flex-1 flex flex-col h-full bg-[#0f1117] border-r border-white/5 py-8 select-none overflow-y-auto font-sans text-sm">
    {{-- PHẦN 1: DASHBOARD --}}
    <div class="px-4 mb-8">
        @php $isActive = request()->routeIs('admin.home.*'); @endphp
        <a href="{{ route('admin.home.index') }}"
            class="group flex items-center text-left px-4 py-3.5 rounded-xl transition-all duration-300 ease-out relative overflow-hidden
           {{ $isActive
              ? 'bg-white/10 text-white shadow-lg shadow-black/20 ring-1 ring-white/10'
              : 'text-gray-400 hover:bg-white/5 hover:text-white'
           }}">

            {{-- Hiệu ứng Glow nhẹ khi Active --}}
            @if($isActive)
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)]"></div>
            @endif

            <i class="fas fa-chart-line w-9 text-lg transition-colors duration-300 {{ $isActive ? 'text-blue-400' : 'group-hover:text-blue-400' }}"></i>
            <span class="font-medium tracking-wide">Dashboard</span>
        </a>
    </div>
    @if(Auth::user()->role_id === 3 || Auth::user()->role_id === 5)
    {{-- SẢN PHẨM --}}
    @php
    $isProductGroup = request()->routeIs('admin.product.*') || request()->routeIs('admin.import.*') || request()->routeIs('admin.category.*') || request()->routeIs('admin.brand.*') || request()->routeIs('admin.color.*') || request()->routeIs('admin.size.*') || request()->routeIs('admin.tag.*');
    @endphp
    <div x-data="{ open: {{ $isProductGroup ? 'true' : 'false' }} }" class="font-bold">
        {{-- Nút Dropdown --}}
        <button @click="open = !open"
            class="w-full flex items-center text-left justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                {{ $isProductGroup
                    ? 'bg-white/5 text-white'
                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                }}">
            <div class="flex items-center">
                <i class="fas fa-box w-9 text-lg transition-colors {{ $isProductGroup ? 'text-purple-400' : 'group-hover:text-purple-400' }}"></i>
                <span class="font-medium">Quản lý sản phẩm</span>
            </div>
            <i class="fas fa-chevron-right text-[10px] transition-transform duration-300 {{ $isProductGroup ? 'text-white' : 'text-gray-600' }}" :class="open ? 'rotate-90' : ''"></i>
        </button>

        {{-- Menu Con --}}
        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-2 space-y-1 pl-12 pr-2 relative">
            <div class="absolute left-8 top-2 bottom-2 w-px bg-white/10"></div>
            {{-- Danh sách sản phẩm --}}
            <a href="{{ route('admin.product.index') }}"
                class="flex items-start py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.product.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Sản phẩm
            </a>

            <a href="{{ route('admin.category.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.category.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Danh mục
            </a>

            <a href="{{ route('admin.brand.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.brand.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Thương hiệu
            </a>

            <a href="{{ route('admin.color.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.color.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Màu sắc
            </a>

            <a href="{{ route('admin.size.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.size.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Kích cỡ
            </a>

            <a href="{{ route('admin.tag.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.tag.*')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Nhãn
            </a>
        </div>
    </div>
    @php
    $isImportGroup = request()->routeIs('admin.import.*');
    @endphp
    <div x-data="{ open: {{ $isImportGroup ? 'true' : 'false' }} }" class="font-bold">
        {{-- Nút Dropdown --}}
        <button @click="open = !open"
            class="w-full flex items-center text-left justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                {{ $isImportGroup
                    ? 'bg-white/5 text-white'
                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                }}">
            <div class="flex items-center">
                <i class="fas fa-boxes-stacked w-9 text-lg transition-colors {{ $isImportGroup ? 'text-purple-400' : 'group-hover:text-purple-400' }}"></i>
                <span class="font-medium">Quản lý nhập hàng</span>
            </div>
            <i class="fas fa-chevron-right text-[10px] transition-transform duration-300 {{ $isImportGroup ? 'text-white' : 'text-gray-600' }}" :class="open ? 'rotate-90' : ''"></i>
        </button>

        {{-- Menu Con --}}
        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-2 space-y-1 pl-12 pr-2 relative">
            <div class="absolute left-8 top-2 bottom-2 w-px bg-white/10"></div>
            <a href="{{ route('admin.import.index') }}"
                class="flex items-start py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.import.index')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Xem phiếu nhập
            </a>

            <a href="{{ route('admin.import.create') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.import.create')
                      ? 'text-white font-medium bg-white/10'
                      : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Tạo phiếu nhập
            </a>
        </div>
    </div>
    @endif
    @if(Auth::user()->role_id === 3 || Auth::user()->role_id === 5 || Auth::user()->role_id === 4)
    {{-- BÁN HÀNG --}}
    @php
    $isOrderGroup = request()->routeIs('admin.order.*') || request()->routeIs('admin.ship.*');
    @endphp
    <div x-data="{ open: {{ $isOrderGroup ? 'true' : 'false' }} }" class="font-bold">
        <button @click="open = !open"
            class="w-full flex items-center text-left justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                    {{ $isOrderGroup ? 'bg-white/5 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <div class="flex items-center">
                <i class="fas fa-list w-9 text-lg transition-colors {{ $isOrderGroup ? 'text-teal-400' : 'group-hover:text-teal-400' }}"></i>
                <span class="font-medium">Quản lý đơn hàng</span>
            </div>
            <i class="fas fa-chevron-right text-[10px] transition-transform duration-300 {{ $isOrderGroup ? 'text-white' : 'text-gray-600' }}"
                :class="open ? 'rotate-90' : ''"></i>
        </button>

        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-2 space-y-1 pl-12 pr-2 relative">
            <div class="absolute left-8 top-2 bottom-2 w-px bg-white/10"></div>
            @if(Auth::user()->role_id === 3 || Auth::user()->role_id === 5)
            <a href="{{ route('admin.order.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.order.index') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Đơn hàng
            </a>
            @endif
            @if(Auth::user()->role_id === 4 || Auth::user()->role_id === 5)
            <a href="{{ route('admin.order.ship') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.order.ship') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Đơn hàng cần giao
            </a>
            <a href="{{ route('admin.order.accepted') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.order.accepted') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Đơn hàng đã nhận
            </a>
            @endif
        </div>
    </div>
    @endif
    @if(Auth::user()->role_id === 2 || Auth::user()->role_id === 5)
    {{-- Người dùng --}}
    @php
    $isUserGroup = request()->routeIs('admin.user.*') || request()->routeIs('admin.ship.*');
    @endphp
    <div x-data="{ open: {{ $isUserGroup ? 'true' : 'false' }} }" class="font-bold">
        <button @click="open = !open"
            class="w-full flex items-center text-left justify-between px-4 py-3.5 rounded-xl transition-all duration-300 group
                    {{ $isUserGroup ? 'bg-white/5 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <div class="flex items-center">
                <i class="fas fa-users w-9 text-lg transition-colors {{ $isUserGroup ? 'text-teal-400' : 'group-hover:text-teal-400' }}"></i>
                <span class="font-medium">Quản lý người dùng</span>
            </div>
            <i class="fas fa-chevron-right text-[10px] transition-transform duration-300 {{ $isUserGroup ? 'text-white' : 'text-gray-600' }}"
                :class="open ? 'rotate-90' : ''"></i>
        </button>

        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-2 space-y-1 pl-12 pr-2 relative">
            <div class="absolute left-8 top-2 bottom-2 w-px bg-white/10"></div>

            <a href="{{ route('admin.user.index') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.user.index') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Người dùng
            </a>
            @if(Auth::user()->role_id === 5)
            <a href="{{ route('admin.user.account') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.user.account') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Tài khoản
            </a>
            @endif
            <a href="{{ route('admin.user.review') }}"
                class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 relative group/item
                   {{ request()->routeIs('admin.user.review') ? 'text-white font-medium bg-white/10' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                Quản lý bình luận
            </a>
        </div>
    </div>
    @endif
    @if(Auth::user()->role_id === 5)
    <div class="mt-2">
        @php $isActive = request()->routeIs('admin.slideshow.*'); @endphp
        <a href="{{ route('admin.slideshow.index') }}"
            class="group flex items-center text-left px-4 py-3.5 rounded-xl transition-all duration-300 ease-out relative overflow-hidden
               {{ $isActive
                  ? 'bg-white/10 text-white shadow-lg shadow-black/20 ring-1 ring-white/10'
                  : 'text-gray-400 hover:bg-white/5 hover:text-white'
               }}">
            @if($isActive)
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-pink-500 shadow-[0_0_10px_rgba(236,72,153,0.6)]"></div>
            @endif
            <i class="fa-solid fa-images w-9 text-lg transition-colors duration-300 {{ $isActive ? 'text-pink-400' : 'group-hover:text-pink-400' }}"></i>
            <span class="font-medium tracking-wide">Slideshow</span>
        </a>
    </div>

    {{-- CÀI ĐẶT --}}
    <div class="px-4 pb-6 mt-auto">
        <div class="h-px w-full bg-gradient-to-r from-transparent via-white/10 to-transparent mb-6"></div>

        @php $isActive = request()->routeIs('admin.contact.*'); @endphp
        <a href="{{ route('admin.contact.index') }}"
            class="group flex items-center text-left px-4 py-3.5 rounded-xl transition-all duration-300
                {{ $isActive
                    ? 'bg-white/10 text-white'
                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                }}">
            <i class="fas fa-cog w-9 text-lg transition-all duration-300 {{ $isActive ? 'text-gray-200' : 'group-hover:text-white group-hover:rotate-90' }}"></i>
            <span class="font-medium tracking-wide">Cấu hình hệ thống</span>
        </a>
    </div>
    @endif
</nav>
