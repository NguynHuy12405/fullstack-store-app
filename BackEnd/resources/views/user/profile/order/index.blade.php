@extends('user.layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">Đơn hàng của tôi</h1>

    {{-- Thông báo --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabs trạng thái --}}
    <div class="flex gap-3 mb-6">
        <a href="{{ route('user.profile.order.index') }}"
           class="px-4 py-2 rounded
           {{ !$statusId ? 'bg-blue-600 text-white' : 'bg-white border' }}">
            Tất cả
        </a>

        @foreach ($statuses as $status)
            <a href="{{ route('user.profile.order.index', ['status' => $status->id]) }}"
               class="px-4 py-2 rounded
               {{ $statusId == $status->id ? 'bg-blue-600 text-white' : 'bg-white border' }}">
                {{ $status->name }}
            </a>
        @endforeach
    </div>

    {{-- Bảng đơn hàng --}}
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">Mã đơn</th>
                    <th class="p-3">Ngày đặt</th>
                    <th class="p-3">Trạng thái</th>
                    <th class="p-3">Tổng tiền</th>
                    <th class="p-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 font-medium">#{{ $order->id }}</td>

                        <td class="p-3">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="p-3">
                            {{-- Badge trạng thái --}}
                            @php
                                $statusColor = match($order->order_status_id) {
                                    1 => 'bg-yellow-100 text-yellow-700',
                                    2 => 'bg-blue-100 text-blue-700',
                                    3 => 'bg-green-100 text-green-700',
                                    4 => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs {{ $statusColor }}">
                                {{ $order->orderStatus->name }}
                            </span>
                        </td>

                        <td class="p-3 font-semibold text-red-600">
                            {{ number_format($order->total_price) }} đ
                        </td>

                        <td class="p-3 text-center">
                            @if ($order->order_status_id == 1)
                                <form
                                    action="{{ route('user.profile.order.cancel', $order->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')"
                                >
                                    @csrf
                                    <button
                                        class="px-3 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600"
                                    >
                                        Hủy đơn
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            Không có đơn hàng nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
@endsection