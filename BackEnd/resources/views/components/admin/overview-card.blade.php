@props(['totalRevenue', 'lowStock', 'totalCustomers', 'completedOrders', 'unprocessedOrders'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Tổng doanh thu</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</h3>
            </div>
            <div class="p-2 bg-green-100 text-green-600 rounded-lg"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <p class="text-green-500 text-xs mt-4 flex items-center font-medium">
            <i class="fas fa-arrow-up mr-1"></i> +12% <span class="text-gray-400 ml-1 font-normal">so với tháng trước</span>
        </p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Đơn hàng mới</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $completedOrders }}</h3>
            </div>
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg"><i class="fas fa-shopping-bag"></i></div>
        </div>
        <p class="text-blue-500 text-xs mt-4 flex items-center font-medium">
            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span> {{ $unprocessedOrders }} đơn chưa xử lý
        </p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Khách hàng</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</h3>
            </div>
            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg"><i class="fas fa-users"></i></div>
        </div>
        <p class="text-green-500 text-xs mt-4 flex items-center font-medium">
            <i class="fas fa-arrow-up mr-1"></i> +3% <span class="text-gray-400 ml-1 font-normal">tuần này</span>
        </p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Tồn kho thấp</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $lowStock }}</h3>
            </div>
            <div class="p-2 bg-red-100 text-red-600 rounded-lg"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <p class="text-red-500 text-xs mt-4 flex items-center font-medium">
            Cần nhập hàng gấp
        </p>
    </div>
</div>