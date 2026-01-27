@props(['order'])
@php
    $totalQty = $order->orderDetails->sum('quantity');
    $subtotal = $order->orderDetails->sum(fn($d) => $d->price * $d->quantity);
@endphp

<div id="orderModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-gray-50 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
                <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 z-20">
                    <div class="flex items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Đơn hàng #ORD-{{ $order->id }}</h3>
                            <p class="text-xs text-gray-500">Đặt lúc: {{ $order->created_at->format('H:i - d/m/Y') }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold
                            @if($order->orderStatus->code === 'pending') bg-yellow-50 text-yellow-700
                            @elseif($order->orderStatus->code === 'completed') bg-green-50 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $order->orderStatus->name }}
                        </span>
                        <span class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600">
                            <i class="fas fa-money-bill-wave mr-1"></i> COD (Thu hộ)
                        </span>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->orderDetails as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-12 w-12 overflow-hidden rounded-md border">
                                                    <img class="h-full w-full object-cover"
                                                        src="{{ $detail->variant->product->thumbnail ?? '' }}"
                                                        alt="">
                                                </div>

                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $detail->variant->product->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Size: {{ $detail->variant->size->name ?? '-' }} |
                                                        Màu: {{ $detail->variant->color->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center text-sm">
                                            {{ number_format($detail->price) }} ₫
                                        </td>

                                        <td class="px-6 py-4 text-center font-medium">
                                            {{ $detail->quantity }}
                                        </td>

                                        <td class="px-6 py-4 text-right font-bold">
                                            {{ number_format($detail->price * $detail->quantity) }} ₫
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <div class="w-full sm:w-80 bg-white rounded-lg border border-gray-200 p-4 shadow-sm space-y-3">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Tạm tính ({{ $totalQty }} sp):</span>
                                    <span class="font-medium">{{ number_format($subtotal) }} ₫</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Phí vận chuyển:</span>
                                    <span class="font-medium">35.000 ₫</span>
                                </div>
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Giảm giá (Voucher):</span>
                                    <span class="font-medium">0 ₫</span>
                                </div>
                                <div class="border-t border-dashed border-gray-300 pt-3 flex justify-between items-center">
                                    <span class="font-bold text-gray-900">Tổng cộng:</span>
                                    <span class="text-xl font-bold text-blue-600">{{ number_format($order->total_price) }} ₫</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ghi chú nội bộ (Chỉ Admin thấy)</label>
                            <div class="flex gap-2">
                                <input type="text" class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white" placeholder="Ví dụ: Khách hẹn giao sau 5h chiều...">
                                <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-md text-sm font-medium border border-gray-300">Lưu</button>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        
                        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">Thông tin khách hàng</h4>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">NV</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-500">Khách hàng thân thiết</p>
                                </div>
                            </div>
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-envelope mt-1 text-gray-400 w-4"></i>
                                    <span class="break-all">{{ $order->user->email }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-phone text-gray-400 w-4"></i>
                                    <span>{{ $order->user->phone }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt mt-1 text-gray-400 w-4"></i>
                                    <span>{{ $order->address }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">Lịch sử đơn hàng</h4>
                            
                            <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1 h-3 w-3 rounded-full bg-gray-300 ring-4 ring-white"></div>
                                    <p class="text-xs text-gray-500 mb-0.5">14:30 - 12/01/2024</p>
                                    <p class="text-sm font-medium text-gray-900">Đơn hàng được tạo</p>
                                    <p class="text-xs text-gray-500">Bởi: Khách hàng</p>
                                </div>
                                </div>
                            
                            <button class="w-full mt-4 text-xs text-blue-600 hover:underline">Xem toàn bộ lịch sử</button>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sticky bottom-0 z-20">
                    <button class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 w-full sm:w-auto">
                        <i class="fas fa-print mr-2"></i> In hóa đơn
                    </button>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="relative flex-1 sm:flex-none">
                            <select class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option>Chờ xử lý</option>
                                <option>Đã xác nhận</option>
                                <option>Đang giao hàng</option>
                                <option>Hoàn thành</option>
                                <option class="text-red-600">Đã hủy</option>
                            </select>
                        </div>
                        
                        <button class="inline-flex items-center justify-center rounded-md bg-blue-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 w-full sm:w-auto">
                            Cập nhật
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
