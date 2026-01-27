@extends('admin.layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('link')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endsection
@section('head-script')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@endsection
@section('style')
<style>
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>
@endsection
@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar" x-data="importManager()">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-150px)]">
        <!-- LEFT SECTION - PRODUCT SEARCH -->
        <div class="lg:col-span-2 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Search Header -->
            <form action="{{ url()->current() }}" method="GET">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tìm kiếm sản phẩm</h2>
                    <div class="grid grid-cols-12 gap-3 items-center">
                        <div class="relative col-span-1">
                            <button type="submit" class="w-full px-4 py-2.5 bg-blue-500 text-white rounded-lg text-sm font-bold hover:bg-blue-800 transition-transform active:scale-95">
                                <i class="fas fa-search mr-2"></i>
                            </button>
                        </div>
                        <div class="relative col-span-5 ">
                            <input name="search" value="{{ request('search') }}" type="text" placeholder="Tìm kiếm theo tên sản phẩm hoặc ID..."
                                class="w-full pl-4 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition">
                        </div>
                        <div class="relative col-span-3">
                            <input type="text" name="size" value="{{ request('size') }}" placeholder="Nhập kích cỡ..."
                                class="w-full pl-4 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition">
                        </div>
                        <div class="relative col-span-3">
                            <select x-ref="colorSelect" name="color" class="cursor-pointer uppercase">
                                <option value="" disabled selected hidden>Chọn màu sắc...</option>
                                @foreach ($colors as $color)
                                <option value="{{$color->id}}" data-hex="{{$color->hex_code}}" {{ request('color') == $color->id ? 'selected' : '' }}>{{$color->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Product List -->
            <div class="flex-1 overflow-y-auto">
                @if($variants->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 px-4">
                    <i class="fas fa-search-minus text-gray-200 text-6xl mb-3"></i>
                    <p class="text-gray-500 text-center">Không tìm thấy sản phẩm</p>
                </div>
                @else
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 border-b border-gray-200">
                        <tr class="text-center">
                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã số</th>
                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Màu sắc</th>
                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kích cỡ</th>
                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn kho</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variants as $variant)
                        <tr class="border-b text-center">
                            <td>{{ $variant->id }}</td>
                            <td>{{ $variant->product->name }}</td>
                            <td class="text-left pl-10">
                                <span class="border-2 px-[9px] rounded-full bg-[{{$variant->color->hex_code}}] mr-1"></span>{{ $variant->color->name }}
                            </td>
                            <td>{{ $variant->size->name }}</td>
                            <td>{{ $variant->quantity }}</td>
                            <td class="p-4 flex justify-center">
                                <button type="button" @click="addItems({{ $variant }})" class="text-green-400 hover:text-green-600 p-2 transition">
                                    <i class=" fas fa-plus fa-xl"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    {{ $variants->links() }}
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Danh sách sản phẩm đã chọn
                    <span class="text-sm font-normal text-gray-500"></span>
                </h2>
                <p>
                    Tổng giá trị: <span x-text="items.reduce((sum, item) => sum + (item.price * item.quantity), 0).toLocaleString('vi-VN')" class="font-semibold text-gray-900"></span> VNĐ
                </p>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="divide-y divide-gray-100">
                    <div class="p-3 space-y-3">
                        @foreach ($errors->all() as $error)
                        <li class="text-red-700 text-sm flex items-start">
                            <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                            {{ $error }}
                        </li>
                        @endforeach
                        <span x-ref="itemError" class="text-red-700 text-sm flex items-start"></span>
                        <template x-for="(item, index) in items" :key="item.variant_id">
                            <div class="grid grid-cols-10 gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                <!-- First Column: Product Info Block -->
                                <div class="col-span-5 bg-white p-3 rounded-lg border border-gray-200">
                                    <p class="font-semibold text-gray-900 mb-2 text-sm" x-text="item.product_name"></p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">Size: </span>
                                            <span class="text-sm font-medium text-gray-700" x-text="item.size_name"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500" x-text="item.color_name"></span>
                                            <span class="inline-block px-2 py-1 rounded-full border-2" :style="`background-color: ${getHex(item.color_id)}`"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-4 flex flex-col gap-2">
                                    <div class="flex flex-row items-center gap-1">
                                        <span class="text-xs text-gray-600 font-medium">Giá:</span>
                                        <input type="number" x-model.number="item.price" min="0"
                                            class="w-full px-3 py-2 border-2 border-gray-200 focus:ring-2 focus:ring-blue-400 rounded-lg bg-white text-sm">
                                    </div>
                                    <div class="flex flex-row items-center gap-1">
                                        <span class="text-xs text-gray-600 font-medium">SL:</span>
                                        <input type="number" x-model.number="item.quantity" min="0"
                                            class="w-full px-3 py-2 border-2 border-gray-200 focus:ring-2 focus:ring-blue-400 rounded-lg bg-white text-sm">
                                    </div>
                                </div>

                                <!-- Fourth Column: Delete Button -->
                                <div class="col-span-1 flex items-center justify-center">
                                    <button @click="removeRow(index)" class="px-3 py-2 text-red-500 hover:text-red-700 font-bold text-lg" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-4 border-t border-gray-200 grid grid-row-2 gap-2">
                <div class="flex gap-3 items-center">
                    <input x-ref="inputPrice" type="number" placeholder="Giá nhanh" min="0"
                        class="w-full px-3 py-2 border-2 border-gray-200 focus:ring-2 focus:ring-blue-400 rounded-lg bg-white text-sm">
                    <input x-ref="inputStock" type="number" placeholder="Số lượng" min="0"
                        class="w-full px-3 py-2 border-2 border-gray-200 focus:ring-2 focus:ring-blue-400 rounded-lg bg-white text-sm">
                    <button @click="quickEdit()" type="button"
                        class="px-2.5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        Sửa
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.import.store') }}" method="POST" class="flex flex-1 gap-2">
                        @csrf
                        <input type="hidden" name="items" :value="JSON.stringify(items)">
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <button @click="$refs.itemError.textContent = items.length === 0 ? 'Vui lòng chọn ít nhất 1 sản phẩm để nhập hàng.' : ''; clearStorage()"
                            type="submit"
                            class="flex-1 px-4 py-2.5 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                            <i class="fas fa-check mr-2"></i> Nhập hàng
                        </button>
                    </form>
                    <button @click="items = []" type="button"
                        class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function importManager() {
        return {
            items: JSON.parse(localStorage.getItem('pending_import_items')) || [],
            colorMap: JSON.parse('@json($colors -> pluck("hex_code", "id") ?? [])'),
            init() {
                this.$watch('items', (value) => {
                    localStorage.setItem('pending_import_items', JSON.stringify(value));
                });

                this.tsColor = new TomSelect(this.$refs.colorSelect, {
                    create: false,
                    render: {
                        item: function(data, escape) {
                            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            <span class="w-4 h-4 rounded-full bg-[${escape(data.hex)}] mr-1 border"></span>
                            ${escape(data.text)}
                            </span>`;
                        },
                        option: function(data, escape) {
                            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            <span class="w-4 h-4 rounded-full bg-[${escape(data.hex)}] mr-1 border"></span>
                            ${escape(data.text)}
                            </span>`;
                        }
                    },
                    sortField: {
                        field: "text",
                        order: "asc",
                    },
                });
            },
            addItems(variant) {
                const exists = this.items.find(item => item.variant_id === variant.id);
                if (!exists) {
                    this.items.push({
                        variant_id: variant.id,
                        product_name: variant.product.name,
                        color_id: variant.color_id,
                        size_id: variant.size_id,
                        color_name: variant.color.name,
                        size_name: variant.size.name,
                        price: 0,
                        quantity: 1,
                    });
                }
                console.log(this.items);
            },
            removeRow(index) {
                this.items.splice(index, 1);
            },
            getHex(colorId) {
                return this.colorMap[colorId];
            },
            clearStorage() {
                localStorage.removeItem('pending_import_items');
            },
            quickEdit() {
                this.items.forEach(item => {
                    let price = this.$refs.inputPrice.value;
                    if (price) item.price = Number(price);
                    let quantity = this.$refs.inputStock.value;
                    if (quantity) item.quantity = Number(quantity);
                })
            },
        }
    }
</script>
@endpush
