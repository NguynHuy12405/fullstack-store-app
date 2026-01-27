<header class="relative">
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-300 transition-all duration-300 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-2 flex justify-between items-center">
            {{-- Mobile --}}
            <button class="lg:hidden">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            {{-- Logo --}}
            <x-logo />

            {{-- Search Desktop --}}
            <form method="GET" action="{{ route('user.product.index') }}"
                class="hidden md:flex flex-1 max-w-md mx-auto relative border-b border-gray-300 focus-within:border-black transition"
            >
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm kiếm sản phẩm..."
                    class="w-full py-2 bg-transparent outline-none text-sm placeholder-gray-400 focus-within:border-black"
                >
                <button type="submit"
                        class="absolute right-0 top-2 text-gray-400 hover:text-black">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            {{-- Right --}}
            <div class="flex space-x-6 items-center">
                {{-- Search Mobile --}}
                <div class="max-[768px]:block hidden relative flex items-center" id="search-container">
                    <form action="{{ route('user.product.index') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}"
                            id="search-input" placeholder="Tìm kiếm sản phẩm..."
                            class="w-0 opacity-0 border-b border-black bg-transparent outline-none text-sm transition-all duration-300 pr-8 py-1 absolute right-0 top-[-2px] focus:w-64 focus:opacity-100 placeholder-gray-400"
                        >
                    </form>

                    <button type="submit" id="search-btn" class="hover:scale-110 transition-transform cursor-pointer z-50">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                {{-- Cart --}}
                <a href="{{ route('user.cart.index') }}" class="relative hover:scale-110 transition-transform p-1">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                    {{-- Hiển thị số lượng nếu > 0 --}}
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="cart-count-badge absolute -top-1 -right-1 bg-black text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Account --}}
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        {{-- Chưa đăng nhập --}}
                        <button onclick="openLoginModal()"
                            class="text-xs font-bold uppercase tracking-widest bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200 whitespace-nowrap"
                        >
                            Đăng nhập
                        </button>
                    @endguest
                    @auth
                        <a href="{{ Auth::check() ? route('user.profile.index') : route('login') }}" class="hover:scale-110 transition-transform">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    @endauth
                </div>
            </div>
            
        </div>

        <x-navigation />

        {{-- MOBILE MENU --}}
        <div id="mobile-menu" class="fixed inset-0 z-40 bg-white transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col pt-24 px-6 space-y-6">
            <a href="{{ route('user.product.index', ['gender' => 'men']) }}" class="text-xl  font-bold border-b pb-2">Nam</a>
            <a href="{{ route('user.product.index', ['gender' => 'women']) }}" class="text-xl  font-bold border-b pb-2">Nữ</a>
            <a href="{{ route('user.product.index', ['category' => 'giay']) }}" class="text-xl  font-bold border-b pb-2">Giày</a>
            <a href="{{ route('user.product.index') }}" class="text-xl  font-bold border-b pb-2">Bộ sưu tập</a>
            <a href="{{ route('login') }}" class="text-center w-full py-3 border border-black uppercase font-bold text-sm tracking-widest mt-4">Đăng nhập</a>
            <a href="{{ route('register') }}" class="text-center w-full py-3 bg-black text-white uppercase font-bold text-sm tracking-widest">Đăng ký ngay</a>
        </div>
    </nav>
</header>

@push('scripts')
<script>
    
    // Search Logic
    const searchBtn = document.getElementById('search-btn');
    const searchInput = document.getElementById('search-input');
    const searchContainer = document.getElementById('search-container');
    let isSearchOpen = false;

    searchBtn.addEventListener('click', (e) => {
        if (!isSearchOpen || searchInput.value === '') {
            e.preventDefault();
            if (!isSearchOpen) {
                searchInput.classList.remove('w-0', 'opacity-0');
                searchInput.classList.add('w-48', 'md:w-64', 'opacity-100');
                searchInput.focus();
                isSearchOpen = true;
            } else {
                // If open and empty, close it
                searchInput.classList.remove('w-48', 'md:w-64', 'opacity-100');
                searchInput.classList.add('w-0', 'opacity-0');
                isSearchOpen = false;
            }
        }
        // If open and has value, allow form submit (default action)
    });

    document.addEventListener('click', (e) => {
        if (!searchContainer.contains(e.target) && isSearchOpen) {
            if(searchInput.value === '') {
                searchInput.classList.remove('w-48', 'md:w-64', 'opacity-100');
                searchInput.classList.add('w-0', 'opacity-0');
                isSearchOpen = false;
            }
        }
    });

    // Mobile Menu Logic
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    let isMenuOpen = false;

    mobileBtn.addEventListener('click', () => {
        if (!isMenuOpen) {
            mobileMenu.classList.remove('-translate-x-full');
            mobileBtn.innerHTML = '<i class="fa-solid fa-xmark text-xl"></i>';
        } else {
            mobileMenu.classList.add('-translate-x-full');
            mobileBtn.innerHTML = '<i class="fa-solid fa-bars text-xl"></i>';
        }
        isMenuOpen = !isMenuOpen;
    });
</script>
@endpush