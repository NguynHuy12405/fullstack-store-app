@extends('admin.layouts.app')
@section('title', 'Dashboard Quản Trị')
@section('subtitle', 'Quản trị thống kê cửa hàng')

@section('content')
<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <x-admin.overview-card
        :total-revenue="$totalRevenue"
        :low-stock="$lowStock"
        :totalCustomers="$totalCustomers"
        :completedOrders="$completedOrders"
        :unprocessedOrders="$unprocessedOrders"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-gray-700">Biểu đồ doanh thu (2024)</h4>
                <select id="revenueFilter" class="text-sm border-gray-200 border rounded p-1 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="month">Tháng này</option>
                    <option value="year">Năm nay</option>
                </select>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-700 mb-4">Sản phẩm bán chạy</h4>

            <ul class="space-y-4 overflow-y-auto max-h-72 pr-2">
                @forelse ($topProducts as $product)
                    @php
                        $sold_quantity = $product->variants->flatMap(fn ($variant) => $variant->orderDetails)->sum('quantity')
                    @endphp
                <li class="flex items-center justify-between border-b border-gray-50 pb-3">
                    <div class="flex items-center gap-3">
                            <img
                                src="{{ asset('storage/' . $product->thumbnail) }}"
                                class="w-10 h-10 object-cover rounded bg-gray-100"
                                alt="{{ $product->name }}"
                            >
                        <div>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $product->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    SKU: SP-0{{ $product->id ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <span class="text-sm font-bold text-slate-700">
                            {{ $sold_quantity }} Đã bán
                        </span>
                    </li>
                @empty
                    <li class="text-center text-sm text-gray-500 py-6">
                        Chưa có dữ liệu bán hàng
                    </li>
                @endforelse
            </ul>

        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h4 class="font-bold text-gray-700">Đơn hàng gần đây</h4>
            <a href="{{ route('admin.order.index') }}" class="text-blue-500 text-sm hover:underline">Xem tất cả</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID Đơn</th>
                        <th class="px-6 py-3">Khách hàng</th>
                        <th class="px-6 py-3">Sản phẩm</th>
                        <th class="px-6 py-3">Tổng tiền</th>
                        <th class="px-6 py-3">Trạng thái</th>
                        <th class="px-6 py-3">Ngày đặt</th>
                        <th class="px-6 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($recentOrders as $order)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            #ORD-{{ $order->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->user->name ?? 'Khách' }}
                        </td>
                        <td class="px-6 py-4 truncate max-w-[150px]">
                            @foreach ($order->orderDetails as $detail)
                                {{ $detail->variant?->product?->name }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ number_format($order->total_price) }} ₫
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded
                                @if($order->orderStatus->code == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->orderStatus->code == 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $order->orderStatus->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="openOrderModal({{ $order->id }})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-admin.order-detail :order="$order" />


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openOrderModal(orderId) {
        fetch(`/quan-ly/don-hang/${orderId}`)
            .then(res => res.json())
            .then(order => {
                console.log(order);
                openModal();
            });
    }

    function openModal() {
        const modal = document.getElementById('orderModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('orderModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Dữ liệu từ Laravel
        const monthLabels = @json($dailyLabels);
        const monthData   = @json($dailyRevenueData);

        const yearLabels  = @json($months);
        const yearData    = @json($monthlyRevenueData);

        // Khởi tạo chart (mặc định: Tháng này)
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Doanh thu (Triệu VNĐ)',
                    data: monthData,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value + ' Tr'
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Xử lý select Tháng / Năm
        document.getElementById('revenueFilter').addEventListener('change', function () {
            if (this.value === 'year') {
                revenueChart.data.labels = yearLabels;
                revenueChart.data.datasets[0].data = yearData;
            } else {
                revenueChart.data.labels = monthLabels;
                revenueChart.data.datasets[0].data = monthData;
            }
            revenueChart.update();
        });
    });
</script>
@endsection