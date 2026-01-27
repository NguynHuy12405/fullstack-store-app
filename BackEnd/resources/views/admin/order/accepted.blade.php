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
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng đơn hàng đã nhận</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng đơn hàng chưa giao</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_accepted_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng đơn hàng đã giao</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_shipped_count }}</p>
            </div>
        </div>
    </div>
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
    <div class="mb-6 gap-4 flex justify-between flex-col md:flex-row items-center">
        <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
            <div class="flex gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-96">
                    <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input form="filter-form" value="{{ request('search') }}" name="search" type="text" placeholder="Tìm kiếm theo ID, Tên khách hàng, SDT" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
                </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ request()->fullUrlWithQuery(['ship_status' => '2']) }}" class="px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request('ship_status') == '2'? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-700 border border-yellow-200 hover:bg-yellow-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block mr-2"></span>Đang giao
                </a>
                <a href="{{ request()->fullUrlWithQuery(['ship_status' => '3']) }}" class="px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request('ship_status') == '3' ? 'bg-green-500 text-white' : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block mr-2"></span>Đã giao
                </a>
            </div>
        </form>
        <div id="filter-button"></div>
    </div>
    <x-admin.import-filter />
    @if($orders->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 px-4">
        <div class="relative mb-6">
            <i class="fas fa-search-minus text-gray-200 text-8xl"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Không tìm thấy</h3>
        <p class="text-gray-500 text-center max-w-sm mb-8">
            Hãy thử tìm lại với tham số khác hoặc thêm mới!
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
                    @if(request()->ship_status == 2)
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    @else
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày giao</th>
                    @endif
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
                        <span class="text-xs text-gray-500">Số điện thoại: {{ $order->phone }}</span>
                        <p class="text-xs text-gray-500">Địa chỉ: {{ $order->address }}</p>
                    </td>
                    <td class="p-4 text-sm font-medium">
                        <x-money :value="$order->total_price" />
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        @if($order->order_status_id == 2)
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
                        @elseif($order->order_status_id == 3)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Hoàn thành
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Lỗi hệ thống! {{$order->order_status_id}}
                        </span>
                        @endif
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    @if (request()->ship_status == 2)
                    <td class="p-4 text-sm font-medium" @click.stop>
                        <form method="POST" action="{{ route('admin.order.shipped', $order->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2.5 rounded-lg font-medium bg-green-500 text-white hover:bg-green-600 transition">Hoàn tất</button>
                        </form>
                        <form method="POST" action="{{ route('admin.order.fail', $order->id) }}" class="inline" x-data="ordersManager()" @submit.prevent="openFailOrderModal($event)">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="message" x-ref="failMessage">
                            <button type="submit" class="px-4 py-2.5 rounded-lg font-medium bg-red-500 text-white hover:bg-red-600 transition">Thất bại</button>
                        </form>
                    </td>
                    @else
                    <td class="p-4 text-sm font-medium">
                        {{ $order->updated_at->format('d/m/Y H:i') }}
                    </td>
                    @endif
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
@push('scripts')
<script>
    function ordersManager() {
        return {
            openFailOrderModal(event) {
                const message = prompt("Vui lòng nhập lý do thất bại cho đơn hàng này:");
                if (message !== null) {
                    this.$refs.failMessage.value = message;
                    event.target.submit();
                }
            }
        }
    }
</script>
@endpush
