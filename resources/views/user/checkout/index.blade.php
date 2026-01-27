<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - CDHN</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .input-modern {
            @apply w-full border border-gray-300 rounded-xl px-4 py-3
            text-gray-800 placeholder-gray-400 bg-white
            focus:outline-none focus:ring-2 focus:ring-black focus:border-black
            transition;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

<div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">

    {{-- ================= LEFT: FORM ================= --}}
    <form
        action="{{ route('checkout.store') }}"
        method="POST"
        class="p-8 lg:p-20 bg-white space-y-10"
    >
        @csrf

        <a href="{{ route('user.home.index') }}"
           class="text-2xl font-extrabold tracking-widest uppercase block">
            CDHN
        </a>

        {{-- THÔNG TIN GIAO HÀNG --}}
        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-lg font-semibold mb-6 flex items-center gap-2">
                <i class="fa-solid fa-truck text-gray-500"></i>
                Thông tin giao hàng
            </h2>

            <div class="space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">
                        Tên người nhận
                    </label>
                    <input
                        type="text"
                        name="receiver_name"
                        value="{{ old('receiver_name', $user->name) }}"
                        class="input-modern"
                        required
                    >
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">
                        Số điện thoại
                    </label>
                    <input
                        type="text"
                        name="receiver_phone"
                        value="{{ old('receiver_phone', $user->phone) }}"
                        class="input-modern"
                        required
                    >
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1 block">
                        Địa chỉ giao hàng
                    </label>
                    <input
                        type="text"
                        name="receiver_address"
                        value="{{ old('receiver_address', $user->address) }}"
                        class="input-modern"
                        required
                    >
                </div>
            </div>
        </div>

        {{-- THANH TOÁN --}}
        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-lg font-bold uppercase mb-6">
                Phương thức thanh toán
            </h2>

            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:border-black">
                <input type="radio" name="payment_method" value="cod" checked>
                <span class="font-medium">Thanh toán khi nhận hàng (COD)</span>
            </label>
        </div>

        <button
            type="submit"
            class="w-full bg-black text-white py-4 rounded-xl
                   uppercase tracking-widest text-sm font-bold
                   hover:bg-gray-800 transition">
            Hoàn tất đơn hàng
        </button>
    </form>

    {{-- ================= RIGHT: CART ================= --}}
    <div class="bg-gray-50 p-8 lg:p-20">

        <h2 class="text-lg font-bold uppercase mb-6">
            Giỏ hàng ({{ $cartItems->count() }})
        </h2>

        <div class="space-y-6 mb-8">
            @foreach($cartItems as $item)
                @php
                    $variant = $item->variant;
                    $product = $variant->product;
                    $price = $variant->sale_price > 0
                        ? $variant->sale_price
                        : $variant->price;
                @endphp

                <div class="flex gap-4">
                    <div class="relative w-20 h-24 bg-gray-200 rounded overflow-hidden">
                        <img
                            src="{{ $product->thumbnail
                                ? asset($product->thumbnail)
                                : 'https://via.placeholder.com/200x300' }}"
                            class="w-full h-full object-cover"
                        >
                        <span class="absolute -top-2 -right-2 bg-black text-white text-xs
                            w-5 h-5 flex items-center justify-center rounded-full">
                            {{ $item->quantity }}
                        </span>
                    </div>

                    <div class="flex-1">
                        <h4 class="font-bold">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-500">
                            Size: {{ $variant->size->name }} /
                            Màu: {{ $variant->color->name }}
                        </p>
                    </div>

                    <p class="font-medium">
                        {{ number_format($price * $item->quantity, 0, ',', '.') }} ₫
                    </p>
                </div>
            @endforeach
        </div>

        <div class="border-t pt-6 space-y-2 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>Tạm tính</span>
                <span>{{ number_format($total, 0, ',', '.') }} ₫</span>
            </div>

            <div class="flex justify-between text-xl font-bold pt-4 border-t">
                <span>Tổng cộng</span>
                <span>{{ number_format($total, 0, ',', '.') }} ₫</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
