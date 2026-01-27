@extends('admin.layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('head-script')
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    serif: ['"Playfair Display"', 'serif'],
                    sans: ['"Inter"', 'sans-serif']
                },
                colors: {
                    brand: {
                        black: '#1a1a1a',
                        gray: '#f4f4f5'
                    }
                }
            }
        }
    }
</script>
@endsection
@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar" x-data="ordersManager()">
    <x-admin.import-filter />
    @if (session('error'))
    <div class="flex justify-center m-5">
        <div style="color: #721c24; padding: 10px; border: 1px solid #721c24; background: #f8d7da;">
            {{ session('error') }}
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="mb-8" style="color: #721c24; padding: 10px; border: 1px solid #721c24; background: #f8d7da;">
        Có lỗi, vui lòng kiểm tra lại!
    </div>
    @endif
    @if (session('success'))
    <div class="mb-8" style="color: green; padding: 10px; border: 1px solid green; background: #e9f7ef;">
        {{ session('success') }}
    </div>
    @endif
    @if($orders->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 px-4">
        <div class="relative mb-6">
            <i class="fas fa-search-minus text-gray-200 text-8xl"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Chưa có đơn hàng nào!</h3>
        <p class="text-gray-500 text-center max-w-sm mb-8">
            Hãy đợi quản trị viên xác nhận đơn hàng từ khách hàng!
        </p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">STT</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Mã số</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trị giá</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trạng thái đơn hàng</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            @foreach ($orders as $order)
            <tbody class="divide-y divide-gray-100" x-data="{ open: false }">
                <tr @click="open = !open" class=" group hover:bg-blue-50 border-blue-200 transition" :class="open ? 'bg-gray-50 border-gray-200' : 'bg-white'"
                    class="border-b transition-colors duration-300">
                    <td class="p-4 text-sm font-medium border-r text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $order->id }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src=" {{ asset('storage/'.$order->user->image) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-indigo-500/50 transition-all">
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $order->user->name }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $order->user->email }} - {{ $order->phone }}</span>
                        <p class="text-xs text-gray-500">{{ $order->address }}</p>
                    </td>
                    <td class="p-4 text-sm font-medium">
                        <x-money :value="$order->total_price" />
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đã xác nhận
                            </span>
                            <div class="flex items-center gap-4">
                                <img src=" {{ asset('storage/'.$order->admin->image) }}" class="w-6 h-6 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-indigo-500/50 transition-all">
                                <div>
                                    <p class="text-xs font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $order->admin->name }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $order->admin->email }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="p-4 text-sm font-medium">
                        <form method="POST" action="{{ route('admin.order.accept', $order->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2.5 rounded-lg font-medium bg-green-500 text-white hover:bg-green-600 transition">Nhận đơn</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <template x-if="open">
                        <td colspan="9" class="p-0">
                            <div class="p-4 border-l-4 border-blue-500 ml-6">
                                <table class="w-full text-sm">
                                    <thead class="bg-blue-50 border-b border-gray-200">
                                        <tr class="text-center">
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Màu sắc</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kích cỡ</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá nhập</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Số lượng</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng cộng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderDetails as $detail)
                                        <tr class="border-b text-center font-medium">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $detail->variant->product->name }}
                                                <span class="text-xs text-gray-500 block">{{ $detail->variant->product->category->name}} · {{ $detail->variant->product->brand->name}}</span>
                                            </td>
                                            <td>
                                                <span class="border-2 px-[9px] rounded-full bg-[{{$detail->variant->color->hex_code}}] mr-1"></span>{{ $detail->variant->color->name }}
                                            </td>
                                            <td>{{ $detail->variant->size->name }}</td>
                                            <td><x-money :value="$detail->price" /></td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td><x-money :value="$detail->price * $detail->quantity" /></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </template>
                </tr>
            </tbody>
            @endforeach
        </table>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            {{ $orders->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
