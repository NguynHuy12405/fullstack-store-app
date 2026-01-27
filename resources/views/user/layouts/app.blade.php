<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Fashion Shop')</title>

    {{-- 1. FONTS & ICONS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">

    {{-- 2. CSS LIBRARIES --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    {{-- 3. TAILWINDCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- 4. JAVASCRIPT LIBRARIES --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body>
    @include('user.layouts.header')

    <main class="pt-10">
        @yield('content')
        <x-chatbox.chatbox />
        @include('user.layouts.login')
    </main>
    
    @include('user.layouts.footer')

    @stack('scripts')
    <script>
        //pop up đăng nhập đăng ký
        document.getElementById('loginForm').addEventListener('submit', async function(e){
            e.preventDefault();

            let formData = new FormData(this);

            let res = await fetch("{{ route('ajax.login') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document
                    .querySelector('input[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if(data.success){
                location.reload();
            }else{
                document.getElementById('loginError').innerText = data.message;
            }
        });

        function openLoginModal(){
        const modal = document.getElementById('loginModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        }

        function closeLoginModal(){
        const modal = document.getElementById('loginModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        }

        function toggleWishlistGlobal(button, productId) {
            // 1. Kiểm tra đăng nhập
            @guest
                Swal.fire({
                    icon: 'warning',
                    title: 'Vui lòng đăng nhập',
                    text: 'Bạn cần đăng nhập để lưu sản phẩm này.',
                    confirmButtonColor: '#000',
                    confirmButtonText: 'Đăng nhập',
                    showCancelButton: true,
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = "{{ route('login') }}";
                });
                return;
            @endguest

            // 2. Xác định trạng thái TRƯỚC khi bấm
            const icon = button.querySelector('i');
            const isLikedBefore = button.classList.contains('text-red-500'); 
            
            // 3. Cấu hình Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 4. Xử lý UI (Optimistic UI)
            if (isLikedBefore) {
                // XÓA
                button.classList.remove('bg-red-50', 'text-red-500');
                button.classList.add('bg-white/80', 'text-gray-400');
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // THÊM
                button.classList.remove('bg-white/80', 'text-gray-400');
                button.classList.add('bg-red-50', 'text-red-500');
                icon.classList.remove('far');
                icon.classList.add('fas');
                
                icon.style.transform = 'scale(1.3)';
                setTimeout(() => icon.style.transform = 'scale(1)', 200);
            }

            // 5. Gửi Request
            fetch("{{ route('user.wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    
                    // HIỂN THỊ THÔNG BÁO
                    if (isLikedBefore) {
                        Toast.fire({
                            icon: 'info',
                            title: 'Đã xóa khỏi yêu thích'
                        });
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'Đã thêm vào yêu thích'
                        });
                    }

                    // XỬ LÝ RIÊNG CHO TRANG WISHLIST (Xóa item)
                    if (window.location.pathname.includes('/wishlist') && isLikedBefore) {
                        const itemToRemove = document.getElementById(`wishlist-item-${productId}`);
                        if(itemToRemove) {
                            itemToRemove.style.transition = "all 0.3s ease";
                            itemToRemove.style.opacity = "0";
                            itemToRemove.style.transform = "scale(0.9)";
                            setTimeout(() => {
                                itemToRemove.remove();
                                // Check empty state
                                const grid = document.getElementById('wishlist-grid');
                                if (grid && grid.children.length === 0) {
                                    grid.classList.add('hidden');
                                    document.getElementById('empty-state').classList.remove('hidden');
                                    document.getElementById('empty-state').classList.add('flex');
                                }
                            }, 300);
                        }
                    }
                } else {
                    Swal.fire('Lỗi', 'Không thể cập nhật danh sách', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.fire({ icon: 'error', title: 'Lỗi kết nối' });
            });
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif
    </script>
</body>
</html>
