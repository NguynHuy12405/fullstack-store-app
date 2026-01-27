@extends('admin.layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng phiếu nhập</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                <i class="fas fa-money-bill"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng chi tiêu</p>
                <p class="text-2xl font-bold text-gray-900"><x-money :value="$counts->total_price_count" /></p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-cube"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng lượng hàng đã nhập</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_items_count }}</p>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input form="filter-form" value="{{ request('search') }}" name="search" type="text" placeholder="Tìm kiếm theo ID, tên sản phẩm" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto" x-data="{}">
            <div id="unfill-button"></div>
            <div id="fill-button"></div>
            <div id="filter-button"></div>
        </div>
    </div>
    <x-admin.import-filter />
    @if($imports->isEmpty())
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Người nhập</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng giá trị</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày nhập</th>
                </tr>
            </thead>
            @foreach ($imports as $import)
            <tbody class="divide-y divide-gray-100" x-data="{ open: false }">
                <tr @click="open = !open" class=" group hover:bg-blue-50 border-blue-200 transition" :class="open ? 'bg-gray-50 border-gray-200' : 'bg-white'"
                    class="border-b transition-colors duration-300">
                    <td class="p-4 text-sm font-medium border-r text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $import->id }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src=" {{ asset('storage/'.$import->user->image) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-indigo-500/50 transition-all">
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $import->user->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium">
                        <x-money :value="$import->total_price" />
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $import->created_at->format('d/m/Y H:i') }}
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
                                        @foreach($import->importDetails as $detail)
                                        <tr class="border-b text-center font-medium">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detail->variant->product->name }}</td>
                                            <td>
                                                <span class="border-2 px-2.5 rounded-full bg-[{{$detail->variant->color->hex_code}}] mr-1"></span>{{ $detail->variant->color->name }}
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
            {{ $imports->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
