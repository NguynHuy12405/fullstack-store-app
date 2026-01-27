@extends('user.layouts.app')
@section('title', 'Tài khoản của tôi')
@section('content')
<div class="max-w-6xl mx-auto pt-20 pb-10 px-4 flex flex-col md:flex-row gap-8 bg-gray-50  text-gray-800">
    <!-- Sidebar -->
    <x-sidebar.profile-sidebar />

    <!-- Content -->
    <main class="flex-1 bg-white p-8 shadow-sm">
        <div class="mb-8 border-b border-gray-100 pb-4">
            <h2 class="text-xl  font-bold">Thông tin tài khoản</h2>
            <p class="text-sm text-gray-400 mt-1">Quản lý thông tin hồ sơ và bảo mật của bạn.</p>
        </div>

        <form action="" method="POST">
            <div class="flex items-center gap-6 mb-10">
                <div class="relative group cursor-pointer">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                        <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                    <input type="file" class="hidden">
                </div>
                <div>
                    <button type="button" class="text-xs font-bold uppercase tracking-widest border border-gray-300 px-4 py-2 hover:bg-black hover:text-white transition">
                        Đổi ảnh đại diện
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2">Định dạng: JPG, PNG. Tối đa 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-10">
                <div class="group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Họ và tên</label>
                    <input type="text" value="{{$user->name}}" readonly class="w-full border-b border-gray-300 py-2 focus:border-black outline-none transition bg-transparent font-medium text-gray-800">
                </div>

                <div class="group opacity-70">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2">Email (Không thể thay đổi)</label>
                    <input type="email" value="{{$user->email}}" readonly class="w-full border-b border-gray-200 py-2 outline-none bg-transparent text-gray-500 cursor-not-allowed">
                </div>

                <div class="group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Số điện thoại</label>
                    <input type="tel" value="{{$user->phone}}" readonly class="w-full border-b border-gray-300 py-2 focus:border-black outline-none transition bg-transparent font-medium text-gray-800">
                </div>

                <div class="group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Địa Chỉ</label>
                    <input type="text" value="{{$user->address}}" readonly class="w-full border-b border-gray-300 py-2 focus:border-black outline-none transition bg-transparent font-medium text-gray-800 text-sm">
                </div>
                <div class="relative group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Password</label>
    <input type="password" id="password" class="border px-3 py-2 w-full pr-10" readonly value="{{$user->password}}">
</div>
                
                <div class="group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Mã Khách hàng</label>
                    <input type="text" value="MKH{{$user->id}}" readonly class="w-full border-b border-gray-300 py-2 focus:border-black outline-none transition bg-transparent font-medium text-gray-800 text-sm">
                </div>
                <div class="group">
                    <label class="block text-xs uppercase font-bold text-gray-500 mb-2 group-focus-within:text-black transition">Giới tính</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="gender" class="w-4 h-4 text-black focus:ring-black" checked>
                            <span class="ml-2 text-sm text-gray-700">Nam</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="gender" class="w-4 h-4 text-black focus:ring-black">
                            <span class="ml-2 text-sm text-gray-700">Nữ</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="gender" class="w-4 h-4 text-black focus:ring-black">
                            <span class="ml-2 text-sm text-gray-700">Khác</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-12 flex justify-end gap-4">
                <button
                    type="button"
                    onclick="openModal()"
                    class="px-8 py-3 text-xs font-bold uppercase tracking-widest bg-black text-white hover:bg-gray-800 transition shadow-lg shadow-black/20">
                    Đổi Mật Khẩu
                </button>
            </div>
        </form>

        <div id="pass-modal" class="fixed inset-0 z-50 hidden">
            <div id="modal-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div id="modal-panel" class="bg-white w-full max-w-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center p-6 border-b border-gray-100">
                        <h3 class=" text-2xl font-bold">Bảo mật tài khoản</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="p-8 overflow-y-auto custom-scrollbar">
                        <form action="#" method="POST" class="space-y-6">
                            <div class="relative group">
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Mật khẩu hiện tại</label>
                                <div class="relative">
                                    <input type="password" 
                                        placeholder="Nhập mật khẩu đang sử dụng" 
                                        class="password-input w-full border-b border-gray-200 py-3 pr-10 text-sm outline-none focus:border-black transition placeholder-gray-300 bg-transparent">
                                    
                                    <button type="button" class="toggle-password absolute right-0 top-3 text-gray-400 hover:text-black transition">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                
                                <div class="flex justify-end mt-2">
                                    <a href="#" class="text-xs text-gray-400 hover:text-black underline decoration-gray-300 underline-offset-4 transition">Quên mật khẩu?</a>
                                </div>
                            </div>

                            <div class="relative group mt-8">
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" 
                                        placeholder="Tối thiểu 8 ký tự" 
                                        class="password-input w-full border-b border-gray-200 py-3 pr-10 text-sm outline-none focus:border-black transition placeholder-gray-300 bg-transparent">
                                    
                                    <button type="button" class="toggle-password absolute right-0 top-3 text-gray-400 hover:text-black transition">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <ul class="mt-3 space-y-1 pl-1">
                                    <li class="text-[10px] text-gray-400 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span> Tối thiểu 8 ký tự
                                    </li>
                                    <li class="text-[10px] text-gray-400 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span> Bao gồm chữ hoa và số
                                    </li>
                                </ul>
                            </div>

                            <div class="relative group">
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Nhập lại mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" 
                                        placeholder="Xác nhận lại mật khẩu" 
                                        class="password-input w-full border-b border-gray-200 py-3 pr-10 text-sm outline-none focus:border-black transition placeholder-gray-300 bg-transparent">
                                    
                                    <button type="button" class="toggle-password absolute right-0 top-3 text-gray-400 hover:text-black transition">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6 border-t border-gray-100 flex justify-end gap-4 bg-gray-50">
                                <button
                                    type="button"
                                    onclick="closeModal()"
                                    class="px-6 py-3 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black hover:underline">
                                    Hủy bỏ
                                </button>
                                <button class="bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg">Lưu Mật Khẩu</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('pass-modal');
        const modalBackdrop = document.getElementById('modal-backdrop');
        const modalPanel = document.getElementById('modal-panel');
        function togglePassword() {
        const input = document.getElementById("password");
        input.type = input.type === "password" ? "text" : "password";
}
        function openModal() {
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalPanel.classList.remove('opacity-0', 'scale-95');
                modalPanel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            modalBackdrop.classList.add('opacity-0');
            modalPanel.classList.remove('opacity-100', 'scale-100');
            modalPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        modalBackdrop.addEventListener('click', closeModal);
    </script>
@endpush

