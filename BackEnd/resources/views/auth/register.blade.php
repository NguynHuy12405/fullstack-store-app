<x-guest-layout>
    <div class="w-full max-w-2xl mx-auto bg-white p-8 sm:p-12 shadow-2xl rounded-2xl relative overflow-hidden">
        
        {{-- Decorative Background Element (Optional) --}}
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gray-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="text-center mb-10 relative z-10">
            <h2 class="text-3xl font-bold font-serif text-gray-900 mb-2">Đăng ký thành viên</h2>
            <p class="text-sm text-gray-500">Trở thành thành viên để nhận ưu đãi độc quyền</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6 relative z-10" x-data="{ showPass: false }">
            @csrf

            {{-- Row 1: Name & Phone --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="group">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                        Họ và tên
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="far fa-user text-gray-400 group-focus-within:text-black transition-colors"></i>
                        </div>
                        <input type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Họ và tên"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="group">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                        Số điện thoại
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-phone-alt text-gray-400 group-focus-within:text-black transition-colors text-xs"></i>
                        </div>
                        <input type="text" name="phone" :value="old('phone')" required placeholder="Số điện thoại"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
            </div>

            {{-- Row 2: Email --}}
            <div class="group">
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="far fa-envelope text-gray-400 group-focus-within:text-black transition-colors"></i>
                    </div>
                    <input type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@gmail.com"
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            {{-- Row 3: Password & Confirm --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="group">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-black transition-colors"></i>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="group">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                        Nhập lại mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-check-circle text-gray-400 group-focus-within:text-black transition-colors text-xs"></i>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
                        
                        {{-- Toggle Show/Hide (Chung cho cả 2 ô) --}}
                        <button type="button" @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-black focus:outline-none cursor-pointer transition-colors"
                            title="Hiện/Ẩn mật khẩu">
                            <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>

            {{-- Terms & Conditions Checkbox --}}
            <div class="flex items-center">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" required class="w-4 h-4 border-gray-300 rounded text-black focus:ring-black cursor-pointer">
                    <span class="ml-2 text-xs text-gray-500 group-hover:text-black transition-colors">
                        Tôi đồng ý với <a href="#" class="underline font-bold">Điều khoản dịch vụ</a> & <a href="#" class="underline font-bold">Chính sách bảo mật</a>
                    </span>
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                class="w-full bg-black text-white text-xs font-bold uppercase py-4 rounded-lg tracking-widest hover:bg-gray-800 hover:shadow-lg transform active:scale-[0.99] transition-all duration-200">
                Đăng ký tài khoản
            </button>
        </form>

        {{-- Divider & Social --}}
        <div class="mt-8 relative z-10">
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink-0 mx-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest">Hoặc đăng ký với</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-100 rounded-lg bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all">
                    <i class="fab fa-google text-red-500 text-sm"></i> Google
                </button>
                <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-100 rounded-lg bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all">
                    <i class="fab fa-facebook text-blue-600 text-sm"></i> Facebook
                </button>
            </div>
            
            <div class="text-center mt-6">
                <a class="text-xs text-gray-500 hover:text-black transition-colors" href="{{ route('user.home.index') }}">
                    Đã là thành viên? <span class="font-bold underline text-black">Đăng nhập ngay</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>