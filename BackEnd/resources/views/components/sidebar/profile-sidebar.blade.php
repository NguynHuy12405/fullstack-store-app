@php
    $active = 'bg-black text-white font-medium';
    $normal = 'text-gray-600 hover:bg-gray-100';
@endphp


<aside class="w-full md:w-64 bg-white p-6 shadow-sm h-fit rounded">
    {{-- User Info --}}
    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-100">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-xl ">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400">Thành viên</p>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="space-y-2">
        <a href="{{ route('user.profile.index') }}"
           class="block px-4 py-2 rounded text-sm transition
           {{ request()->routeIs('user.profile.index') ? $active : $normal }}">
            Thông tin tài khoản
        </a>

        <a href="{{ route('user.profile.wishlist.index') }}"
           class="block px-4 py-2 rounded text-sm transition
           {{ request()->routeIs('user.profile.wishlist.index') ? $active : $normal }}">
            Danh sách yêu thích
        </a>

        <a href="{{ route('user.profile.order.index') }}"
           class="block px-4 py-2 rounded text-sm transition
           {{ request()->routeIs('user.profile.order.*') ? $active : $normal }}">
            Lịch sử đơn hàng
        </a>
        <a href="{{ route('user.reviews.index') }}"
           class="block px-4 py-2 rounded text-sm transition
           {{ request()->routeIs('user.reviews.index') ? $active : $normal }}">
            Lịch sử đánh giá
        </a>

        @if (Auth::user()?->isAdmin())
            <a href="{{ route('admin.home.index') }}" class="block px-4 py-2 rounded text-sm transition {{ request()->routeIs('admin.*') }}">
                Admin Dashboard
            </a>
        @endif


        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="pt-4 mt-4 border-t border-gray-100">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50 rounded text-sm transition">
                Đăng xuất
            </button>
        </form>

    </nav>
</aside>