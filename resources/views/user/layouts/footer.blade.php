<footer class="bg-white border-t border-gray-100 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6">
        @if(!Auth::check())
        <div class="flex flex-col items-center text-center mb-16 max-w-2xl mx-auto">
            <h2 class=" text-3xl md:text-4xl font-medium mb-4">Join The World</h2>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                Đăng ký để nhận những cập nhật mới nhất về bộ sưu tập, sự kiện nghệ thuật và ưu đãi độc quyền dành riêng cho thành viên.
            </p>
            <form class="flex w-full max-w-md border-b border-black">
                <input type="email" placeholder="Nhập địa chỉ email của bạn..." class="w-full py-3 bg-transparent outline-none placeholder-gray-400 text-sm">
                <button type="button" class="uppercase text-xs font-bold tracking-widest py-3 hover:text-gray-600 transition">
                    Gửi
                </button>
            </form>
        </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 border-t border-gray-100 pt-12">
            <x-contact/>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-6">Cửa hàng</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{route('user.product.index')}}" class="hover:text-black hover:underline underline-offset-4 transition">Thời trang Nữ</a></li>
                    <li><a href="{{route('user.product.index')}}" class="hover:text-black hover:underline underline-offset-4 transition">Thời trang Nam</a></li>
                    <li><a href="{{route('user.product.index')}}" class="hover:text-black hover:underline underline-offset-4 transition">Giày</a></li>
                    <li><a href="#" class="hover:text-black hover:underline underline-offset-4 transition">Bộ sưu tập mới</a></li>
                    <li><a href="#" class="hover:text-black hover:underline underline-offset-4 transition text-red-500">Sale Off</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-6">Hỗ trợ</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-black hover:underline underline-offset-4 transition">Tra cứu đơn hàng</a></li>
                    <li><a href="#" class="hover:text-black hover:underline underline-offset-4 transition">Chính sách đổi trả</a></li>
                    <li><a href="#" class="hover:text-black hover:underline underline-offset-4 transition">Hướng dẫn chọn size</a></li>
                    <li><a href="{{route('help.index')}}" class="hover:text-black hover:underline underline-offset-4 transition">Liên hệ hỗ trợ</a></li>
                    <li><a href="{{route('user.contact')}}" class="hover:text-black hover:underline underline-offset-4 transition">Danh sách liên hệ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-6">Về chúng tôi</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{ route('terms') }}" class="hover:text-black hover:underline underline-offset-4 transition">Điều khoản dịch vụ</a></li>
                    <li><a href="{{ route('policy') }}"class="hover:text-black hover:underline underline-offset-4 transition">Chính sách bảo mật</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-16 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">© 2026 CDHN Studio. All rights reserved.</p>

            <div class="flex gap-4 text-gray-300 text-xl">
                <i class="fab fa-cc-visa hover:text-gray-600 transition"></i>
                <i class="fab fa-cc-mastercard hover:text-gray-600 transition"></i>
                <i class="fab fa-cc-paypal hover:text-gray-600 transition"></i>
            </div>
        </div>
    </div>
</footer>
