<header class="h-16 bg-white border-b flex items-center justify-between px-6 shadow-sm z-10">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="text-lg font-semibold text-gray-700">@yield('subtitle')</div>
    </div>

    <div class="flex items-center gap-4">
        <button class="relative text-gray-500 hover:text-blue-500 transition-colors">
            <i class="fas fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
        <div class="h-8 w-px bg-gray-200 mx-2"></div>
        {{-- User Profile --}}
        <div x-data="{ open: false }" class="relative">
            {{-- Trigger --}}
            <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-3 cursor-pointer py-1.5 px-2 rounded-lg hover:bg-white/5 transition-all border border-transparent hover:border-white/5 group">
                {{-- Avatar --}}
                <div class="relative">
                    <img src="{{ asset('storage/'. Auth::user()->image) }}"
                        class="w-8 h-8 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-indigo-500/50 transition-all">
                    {{-- Status Dot --}}
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-[#0f1117] bg-green-500"></span>
                </div>

                {{-- Text Info --}}
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium leading-none mb-1">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-gray-800 uppercase tracking-wider font-bold">{{ Auth::user()->role->name ?? 'Admin' }}</p>
                </div>

                {{-- Chevron Icon --}}
                <i class="fas fa-chevron-down text-[10px] text-gray-500 transition-transform duration-200 hidden md:block" :class="open ? 'rotate-180' : ''"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute right-0 mt-2 w-48 bg-white border border-black/10 rounded-xl shadow-2xl py-1 z-50 overflow-hidden ring-1 ring-black/50">
                <div class="px-4 py-3 border-b border-black/5 mb-1">
                    <p class="text-xs">Đăng nhập bởi</p>
                    <p class="text-sm font-medium truncate">{{ Auth::user()->email }}</p>
                </div>
                <div class="h-px bg-white/5 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2.5 font-bold text-sm text-red-400 hover:bg-rose-100 hover:text-red transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
