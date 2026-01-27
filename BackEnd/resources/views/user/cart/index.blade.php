@extends('user.layouts.app')
@section('title', 'Giỏ hàng')

@section('content')

<div class="pt-24 pb-20 max-w-7xl mx-auto px-6 text-gray-900 bg-white">
    <div class="text-center mb-16 animate-fade-in-up">
        <h1 class="text-4xl  font-bold mb-2">Giỏ hàng của bạn</h1>
        <p class="text-gray-500 text-sm tracking-wide">{{ $items->count() }} sản phẩm trong túi</p>

        <button id="btn-remove-all" onclick="confirmRemoveAll()" 
            class="{{ $items->isEmpty() ? 'hidden' : '' }} text-xs text-red-500 hover:underline"
        >
            Xóa tất cả
        </button>
    </div>

    @if ($items->isEmpty())
        <div class="text-center py-20">
            <p class="text-gray-500 mb-6">Giỏ hàng của bạn đang trống 🛒</p>
            <a href="{{ route('user.home.index') }}" class="text-black underline hover:text-gray-600">Quay lại mua sắm</a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20">
            <div class="w-full lg:w-2/3 space-y-8">
                <div class="hidden md:flex border-b border-gray-200 pb-4 text-xs uppercase tracking-widest text-gray-400 font-bold">
                    <div class="w-1/2">Sản phẩm</div>
                    <div class="w-1/4 text-center">Số lượng</div>
                    <div class="w-1/4 text-right">Tổng</div>
                </div>

                @foreach ($items as $item)
                @php
                    $total = $items->sum(fn($item) => $item->variant->price * $item->quantity);
                    $price = $item->variant->final_price;
                    $lineTotal = $price * $item->quantity;
                    $product = $item->variant->product;
                    $colors = $product->variants->pluck('color')->unique('id')->filter();
                    $sizes = $product->variants->pluck('size')->unique('id')->filter();
                    $variantsData = $product->variants->map(function ($v) {
                        return [
                            'id'       => $v->id,
                            'color_id' => $v->color_id,
                            'size_id'  => $v->size_id,
                            'price'    => $v->sale_price ?? $v->price,
                            'stock'    => $v->quantity,
                        ];
                    });
                @endphp
                    <div
                        data-id="{{ $item->id }}"
                        data-price="{{ $price }}"
                        data-variants='@json($variantsData)'
                        data-route="{{ route('user.cart.update', $item->id) }}"
                        class="cart-item flex flex-col md:flex-row items-center gap-6 border-b border-gray-100 pb-8 group"
                    >
                        <div class="w-full md:w-auto flex justify-center">
                            <input 
                                type="checkbox" 
                                value="{{ $item->id }}"
                                @checked($item->status)
                                class="cart-checkbox w-4 h-4 text-black border-gray-300 rounded focus:ring-black"
                            >
                        </div>  

                        <div class="w-full md:w-24 aspect-[3/4] overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $item->variant->product->thumbnail) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item->variant->product->name }}">
                        </div>
                        
                        <div class="flex-1 text-center md:text-left w-full">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('user.product.show', $item->variant->product->slug) }}" class=" text-xl font-medium hover:underline underline-offset-4">{{ $item->variant->product->name }}</a>
                            </div>

                            <div class="flex items-center gap-2 mt-1 text-sm">
                                {{-- Color --}}
                                <select class="border rounded px-2 py-1"
                                    onchange="updateVariant({{ $item->id }})"
                                    id="color-{{ $item->id }}"
                                >
                                    @foreach ($colors as $color)
                                        <option
                                            value="{{ $color->id }}"
                                            @selected($color->id === $item->variant->color_id)
                                        >
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Size --}}
                                <select class="border rounded px-2 py-1"
                                    onchange="updateVariant({{ $item->id }})"
                                    id="size-{{ $item->id }}"
                                >
                                    @foreach ($sizes as $size)
                                        <option
                                            value="{{ $size->id }}"
                                            @selected($size->id === $item->variant->size_id)
                                        >
                                            Size {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="text-sm font-bold mt-2 md:hidden price">{{ number_format($price, 0, ',', '.') }} ₫</p>
                            
                            <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('user.cart.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-xs text-gray-400 border-b border-gray-300 hover:text-red-500 hover:border-red-500 transition mt-3 pb-0.5 hidden md:inline-block">
                                    Xóa
                                </button>
                            </form>
                        </div>

                        <div class="w-full md:w-auto flex justify-center">
                            <div class="flex items-center border border-gray-300 px-2 py-1">
                                <button type="button" class="btn-dec w-8 h-8 text-gray-500 hover:text-black hover:bg-gray-100 transition">-</button>
                                <input
                                    type="text"
                                    name="quantity"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    max="{{ $item->variant->quantity }}"
                                    class="qty-input w-12 text-center rounded text-sm font-bold"
                                    data-id="{{ $item->id }}"
                                >
                                <button type="button" class="btn-inc w-8 h-8 text-gray-500 hover:text-black hover:bg-gray-100 transition">+</button>
                            </div>
                        </div>

                        <div class="hidden md:block w-24 text-right font-medium line-total">
                            {{ number_format($lineTotal, 0, ',', '.') }} ₫
                        </div>
                    </div>
                @endforeach

                <div class="pt-6">
                    <details class="group cursor-pointer">
                        <summary class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide hover:text-gray-600 transition list-none">
                            <i class="fas fa-tag"></i> Thêm ghi chú hoặc mã giảm giá
                        </summary>
                        <div class="mt-4 flex flex-col md:flex-row gap-4 animate-fade-in-down">
                            <input type="text" placeholder="Ghi chú cho đơn hàng..." class="flex-1 border border-gray-300 px-4 py-3 focus:border-black outline-none text-sm">
                            <div class="flex w-full md:w-1/3">
                                <input type="text" placeholder="Mã giảm giá" class="w-full border border-gray-300 px-4 py-3 border-r-0 focus:border-black outline-none text-sm">
                                <button class="bg-gray-100 px-4 text-xs font-bold uppercase hover:bg-gray-200">Áp dụng</button>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 p-8 lg:sticky lg:top-28">
                    <h3 class=" text-2xl mb-6">Tổng đơn hàng</h3>
                    
                    <div class="space-y-4 border-b border-gray-200 pb-6 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính</span>
                            <span id="sub-total">{{ number_format($total, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Vận chuyển</span>
                            <span class="text-xs italic">Tính khi thanh toán</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <span class="font-bold text-lg uppercase tracking-wide">Tổng cộng</span>
                        <span id="grand-total" class=" text-2xl font-bold">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>

                    <button id="checkoutBtn" class="block w-full bg-black text-white text-center py-4 uppercase tracking-[0.2em] text-xs font-bold hover:bg-gray-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Thanh toán ngay
                    </button>
                    
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-400 mb-2">Chúng tôi chấp nhận:</p>
                        <div class="flex justify-center gap-3 text-2xl text-gray-300">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-apple-pay"></i>
                        </div>
                    </div>

                    <a href="{{ route('user.home.index') }}" class="block text-center text-xs underline text-gray-500 mt-6 hover:text-black">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- PHẦN GỢI Ý SẢN PHẨM --}}
    @if($suggestedProducts->isNotEmpty())
    <div class="mt-24 pt-16 border-t border-gray-100 animate-fade-in-up">
        <div class="text-center mb-12">
            <h3 class=" text-2xl md:text-3xl font-bold mb-3">Có thể bạn sẽ thích</h3>
            <p class="text-gray-500 text-sm">Những lựa chọn tuyệt vời khác dành riêng cho bạn</p>
        </div>

        {{-- Grid sản phẩm --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 gap-y-10">
            @foreach($suggestedProducts as $product)
                <x-products.product-card :product="$product" />
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // URL trang checkout
    const checkoutUrl = "{{ route('user.checkout.index') }}";

    // Lấy CSRF Token từ thẻ meta để bảo mật request
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    // Hàm format tiền tệ
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    };

    // Hàm tính lại tổng tiền trên giao diện (Client-side)
    function recalcCart() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const checkbox = item.querySelector('.cart-checkbox');
            if (!checkbox.checked) return;

            const price = parseInt(item.dataset.price);
            const qty = parseInt(item.querySelector('.qty-input').value);
            const lineTotal = price * qty;
            
            total += lineTotal;

            // Cập nhật text tổng tiền từng dòng
            const lineTotalEl = item.querySelector('.line-total');
            if(lineTotalEl) lineTotalEl.innerText = formatVND(lineTotal);
        });

        // Cập nhật tổng tiền đơn hàng
        const subTotalEl = document.getElementById('sub-total');
        const grandTotalEl = document.getElementById('grand-total');
        if(subTotalEl) subTotalEl.innerText = formatVND(total);
        if(grandTotalEl) grandTotalEl.innerText = formatVND(total);
    };

    // checkbox
    document.querySelectorAll('.cart-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const item = this.closest('.cart-item');
            const updateUrl = item.dataset.route;
            const checked = this.checked;

            fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: checked ? 1 : 0 })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error();
                recalcCart();
            })
            .catch(() => {
                this.checked = !checked;
                recalcCart();
                Swal.fire('Lỗi', 'Không thể cập nhật trạng thái', 'error');
            });
        });
    });

    // 2. Hàm xác nhận xóa tất cả
    function confirmRemoveAll() {
        Swal.fire({
            title: 'Bạn chắc chắn chứ?',
            text: "Toàn bộ sản phẩm trong giỏ hàng sẽ bị xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) removeAllProcess();
        });
    }

    // 3. Xử lý logic xóa tất cả
    function removeAllProcess() {
        const btnAll = document.getElementById('btn-remove-all');
        if(btnAll) btnAll.innerText = 'Đang xóa...';

        fetch("{{ route('user.cart.clear') }}", { 
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {

                const items = document.querySelectorAll('.cart-item');

                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('fade-out');
                        setTimeout(() => item.remove(), 300);
                    }, index * 50);
                });

                setTimeout(() => {
                    recalcCart();
                    checkEmptyCart();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xoá tất cả sản phẩm trong giỏ',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }, items.length * 50 + 400);

            } else {
                Swal.fire('Lỗi', 'Không thể xoá giỏ hàng', 'error');
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire('Lỗi', 'Lỗi kết nối server', 'error');
        })
        .finally(() => {
            if(btnAll) btnAll.innerText = 'Xóa tất cả';
        });
    }

    // Reload để hiện giao diện giỏ trống đúng Blade
    function checkEmptyCart() {
        const items = document.querySelectorAll('.cart-item');
        if (items.length === 0) {
            location.reload();
        }
    }

    // Thông báo xác nhận khi xóa
    function confirmDelete(id) {
        Swal.fire({
            title: 'Bạn chắc chắn muốn xóa?',
            text: "Sản phẩm này sẽ bị xóa khỏi giỏ hàng của bạn.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000', // Màu đen cho hợp theme
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    };

    document.querySelectorAll('.cart-item').forEach(item => {
        const input = item.querySelector('.qty-input');
        const btnInc = item.querySelector('.btn-inc');
        const btnDec = item.querySelector('.btn-dec');

        btnInc?.addEventListener('click', () => {
            const max = parseInt(input.max);
            let val = parseInt(input.value);
            if (val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        btnDec?.addEventListener('click', () => {
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    // === LOGIC AJAX CẬP NHẬT SỐ LƯỢNG ===
    function updateVariant(cartId) {
        const item = document.querySelector(`.cart-item[data-id="${cartId}"]`);
        const variants = JSON.parse(item.dataset.variants);

        const colorSelect = document.getElementById(`color-${cartId}`);
        const sizeSelect  = document.getElementById(`size-${cartId}`);
        const qtyInput    = item.querySelector('.qty-input');
        const priceEl     = item.querySelector('.price');
        const lineTotalEl = item.querySelector('.line-total');

        const colorId = parseInt(colorSelect.value);
        let sizeId = parseInt(sizeSelect.value);

        // === 1. Disable size theo color ===
        const validVariants = variants.filter(v => v.color_id === colorId);
        const validSizeIds = validVariants.map(v => v.size_id);

        [...sizeSelect.options].forEach(opt => {
            const id = parseInt(opt.value);
            opt.disabled = !validSizeIds.includes(id);
        });

        if (!validSizeIds.includes(sizeId)) {
            sizeId = validSizeIds[0];
            sizeSelect.value = sizeId;
        }

        // === 2. Tìm variant ===
        const variant = variants.find(v =>
            v.color_id === colorId && v.size_id === sizeId
        );

        if (!variant) {
            Swal.fire({
                icon: 'error',
                title: 'Biến thể không tồn tại'
            });
            return;
        }

        // === 3. Update UI ngay (Optimistic UI) ===
        item.dataset.price = variant.price;

        const qty = parseInt(qtyInput.value);
        const lineTotal = variant.price * qty;

        if (priceEl) priceEl.innerText = formatVND(variant.price);
        if (lineTotalEl) lineTotalEl.innerText = formatVND(lineTotal);

        qtyInput.max = variant.stock;

        if (qty > variant.stock) {
            qtyInput.value = variant.stock;
        }
        qtyInput.max = variant.stock;

        qtyInput.dataset.oldQty = qtyInput.value;

        recalcCart();

        // === 4. Update DB ===
        fetch(item.dataset.route, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                variant_id: variant.id,
                quantity: parseInt(qtyInput.value)
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.error
                });
            }

            // Nếu merge → reload nhẹ
            if (data.merged) {
                location.reload();
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Không thể cập nhật biến thể'
            });
        });
    }

    // Nút thanh toán
    const btnCheckout = document.getElementById('checkoutBtn');
    if (btnCheckout) {
        btnCheckout.addEventListener('click', e => {
            e.preventDefault();
            window.location.href = checkoutUrl;
        });
    }

    // số lượng sản phẩm
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function () {
            const item = this.closest('.cart-item');
            const updateUrl = item.dataset.route;

            let oldQty = parseInt(this.dataset.oldQty || this.value);
            let newQty = parseInt(this.value);

            // Validate client
            if (isNaN(newQty) || newQty < 1) {
                this.value = oldQty;
                return;
            }

            // Lưu lại số cũ để rollback nếu lỗi
            this.dataset.oldQty = newQty;

            // Optimistic UI
            recalcCart();

            fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Rất tiếc!',
                        text: data.error,
                        confirmButtonColor: '#000'
                    });

                    this.value = data.current_qty || oldQty;
                    recalcCart();
                } else {
                    // Update lại oldQty khi thành công
                    this.dataset.oldQty = newQty;
                }
            })
            .catch(() => {
                this.value = oldQty;
                recalcCart();
                alert('Có lỗi xảy ra, vui lòng thử lại');
            });
        });

        // Ghi nhớ số ban đầu
        input.dataset.oldQty = input.value;
    });

    // Khởi tạo
    document.addEventListener('DOMContentLoaded', recalcCart);
</script>
@endpush