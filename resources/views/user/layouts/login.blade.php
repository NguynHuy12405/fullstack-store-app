<div id="loginModal" class="fixed inset-0 z-[9999] bg-black/50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-[500px] rounded-2xl shadow-2xl relative overflow-hidden transform transition-all">
    <button onclick="closeLoginModal()" 
      class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-black hover:text-white transition duration-200 z-10"
    >
      <i class="fas fa-times"></i>
    </button>

    <div class="p-8 sm:p-10">
      <div class="text-center mb-8">
        <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
          <i class="fas fa-user"></i>
        </div>
        
        <h2 class="text-3xl font-bold font-serif text-gray-900 mb-2">Chào mừng bạn</h2>
        <p class="text-sm text-gray-500">Đăng nhập để nhận ưu đãi & theo dõi đơn hàng</p>
      </div>

      <form id="loginForm" class="space-y-5">
          @csrf

          <div class="group">
              <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5 tracking-widest group-focus-within:text-black transition-colors">
                  Email
              </label>
              <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="far fa-envelope text-gray-400 group-focus-within:text-black transition-colors"></i>
                  </div>
                  <input type="email" name="email" placeholder="name@gmail.com" 
                      class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium">
              </div>
          </div>

          <div class="group" x-data="{ showPass: false }">
              <div class="flex justify-between items-center mb-1.5">
                  <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest group-focus-within:text-black transition-colors">
                      Mật khẩu
                  </label>
                  <a href="#" class="text-[11px] font-medium text-gray-400 hover:text-black hover:underline transition-colors">
                      Quên mật khẩu?
                  </a>
              </div>
              <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400 group-focus-within:text-black transition-colors"></i>
                  </div>
                  
                  <input :type="showPass ? 'text' : 'password'" name="password" placeholder="••••••••" 
                    class="w-full pl-11 pr-11 py-3.5 bg-gray-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-black focus:ring-0 outline-none transition-all placeholder-gray-400 font-medium"
                  >
                  
                  <button type="button" @click="showPass = !showPass" 
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black focus:outline-none cursor-pointer transition-colors"
                  >
                    <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                  </button>
              </div>
          </div>

          <div id="loginError" class="text-red-500 text-xs text-center font-bold min-h-[20px]"></div>

          {{-- Submit Button --}}
          <button type="submit" 
            class="w-full bg-black text-white text-xs font-bold uppercase py-4 rounded-lg tracking-widest hover:bg-gray-800 hover:shadow-lg transform active:scale-[0.98] transition-all duration-200"
          >
            Đăng nhập
          </button>
      </form>

      {{-- 4. Social & Register --}}
      <div class="mt-8">
          <div class="relative flex py-2 items-center">
              <div class="flex-grow border-t border-gray-100"></div>
              <span class="flex-shrink-0 mx-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest">Hoặc</span>
              <div class="flex-grow border-t border-gray-100"></div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-4">
              <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-100 rounded-lg bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all">
                  <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google">
                  Google
              </button>
              <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-100 rounded-lg bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all">
                  <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-4 h-4" alt="Facebook">
                  Facebook
              </button>
          </div>

          <div class="text-center mt-6">
              <p class="text-sm text-gray-500">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="font-bold text-black border-b border-black pb-0.5 hover:text-gray-700 hover:border-gray-700 transition">
                  Đăng ký ngay
                </a>
              </p>
          </div>
      </div>
    </div>
  </div>
</div>