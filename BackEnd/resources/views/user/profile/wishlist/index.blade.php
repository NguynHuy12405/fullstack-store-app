@extends('user.layouts.app')
@section('title', 'Danh sách yêu thích của tôi')
@push('styles')
    <style>
        .fade-out {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto pt-20 pb-10 px-4 flex flex-col md:flex-row gap-8 bg-gray-50 text-gray-800">
        <x-sidebar.profile-sidebar />

        <main class="flex-1 bg-white p-8 shadow-sm rounded-lg min-h-[500px]">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl  font-bold">Sản phẩm đã thích</h2>
                {{-- Nút xóa tất cả --}}
                <button id="btn-remove-all" 
                        onclick="confirmRemoveAll()" 
                        class="{{ $wishlists->isEmpty() ? 'hidden' : '' }} text-xs text-red-500 hover:underline">
                    Xóa tất cả
                </button>
            </div>
            
            {{-- GRID SẢN PHẨM --}}
            <div id="wishlist-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($wishlists as $wishlist)
                    {{-- Wrapper ID để JS Global tìm và xóa --}}
                    <div id="wishlist-item-{{ $wishlist->product->id }}" class="wishlist-item-wrapper transition-all duration-300">
                        {{-- Gọi Component --}}
                        <x-products.product-card :product="$wishlist->product" />
                    </div>
                @endforeach
            </div>

            {{-- Empty State --}}
            <div id="empty-state" class="{{ $wishlists->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                    <i class="far fa-heart text-2xl"></i>
                </div>
                <h3 class="text-lg  font-bold text-gray-800 mb-2">Danh sách yêu thích trống</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-xs">Bạn chưa lưu sản phẩm nào. Hãy khám phá thêm các sản phẩm mới nhé.</p>
                <a href="{{ route('user.home.index') }}" class="px-6 py-2.5 bg-black text-white text-sm font-medium rounded hover:bg-gray-800 transition">
                    Tiếp tục mua sắm
                </a>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
<script>
    const gridObserver = new MutationObserver(function(mutations) {
        checkEmpty();
    });
    const gridElement = document.getElementById('wishlist-grid');
    if (gridElement) {
        gridObserver.observe(gridElement, { childList: true });
    }

    // 2. Hàm xác nhận xóa tất cả
    function confirmRemoveAll() {
        Swal.fire({
            title: 'Bạn chắc chắn chứ?',
            text: "Toàn bộ danh sách yêu thích sẽ bị xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                removeAllProcess();
            }
        })
    }

    // 3. Xử lý logic xóa tất cả
    function removeAllProcess() {
        // Hiệu ứng Loading
        const btnAll = document.getElementById('btn-remove-all');
        if(btnAll) btnAll.innerText = 'Đang xóa...';

        fetch("{{ route('user.wishlist.clear') }}", { 
            method: 'DELETE', // Hoặc POST tùy route bạn định nghĩa
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' || data.message) {
                const items = document.querySelectorAll('.wishlist-item-wrapper');

                // Hiệu ứng xóa lần lượt cho đẹp
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('fade-out');
                    }, index * 50); // Delay nhẹ giữa các item
                });

                // Sau khi animation xong thì dọn dẹp HTML
                setTimeout(() => {
                    document.getElementById('wishlist-grid').innerHTML = '';
                    checkEmpty(); // Hàm này sẽ tự hiện Empty State
                    
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500
                    });
                    Toast.fire({ icon: 'success', title: 'Đã xóa tất cả' });

                }, items.length * 50 + 300);
            } else {
                Swal.fire('Lỗi', 'Không thể xóa danh sách', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Lỗi', 'Lỗi kết nối server', 'error');
        })
        .finally(() => {
             if(btnAll) btnAll.innerText = 'Xóa tất cả';
        });
    }

    // 4. Hàm kiểm tra trạng thái trống
    function checkEmpty() {
        const grid = document.getElementById('wishlist-grid');
        const emptyState = document.getElementById('empty-state');
        const removeAllBtn = document.getElementById('btn-remove-all');
        const sidebarCount = document.getElementById('sidebar-count');

        // Đếm số phần tử con trực tiếp còn lại trong grid
        const count = grid.children.length;

        // Cập nhật số lượng bên Sidebar (nếu có)
        if(sidebarCount) sidebarCount.innerText = count;

        if (count === 0) {
            grid.classList.add('hidden');
            emptyState.classList.remove('hidden');
            emptyState.classList.add('flex');
            if(removeAllBtn) removeAllBtn.style.display = 'none';
        } else {
            grid.classList.remove('hidden');
            emptyState.classList.add('hidden');
            emptyState.classList.remove('flex');
            if(removeAllBtn) removeAllBtn.style.display = 'inline-block';
        }
    }

    5. Thông báo giỏ hàng
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif
</script>
@endpush