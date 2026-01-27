@extends('admin.layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('link')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endsection
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    window.axios = axios;
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
</script>
@endsection
@section('style')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    body {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f1f1f1;
        font-family: "Inter", sans-serif;
    }
</style>
@endsection
@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-cube"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng sản phẩm</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                <i class="fas fa-eye-slash"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Không hoạt động</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->inactive_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Đang hoạt động</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->active_count }}</p>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
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
    </div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.product.trash') }}">
                <button class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class="fas fa-trash mr-2"></i> Thùng rác
                </button>
            </a>
            <div class="relative w-full md:w-96">
                <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input form="filter-form" value="{{ request('search') }}" name="search" type="text" placeholder="Tìm kiếm theo ID, tên sản phẩm" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto" x-data="{}">
            <div id="unfill-button"></div>
            <div id="fill-button"></div>
            <div id="filter-button"></div>
            <button @click="$dispatch('open-add-modal')" class="px-5 py-2.5 bg-black text-white rounded-lg text-sm font-bold hover:bg-gray-800 shadow-lg shadow-black/20 flex items-center gap-2 transition-transform active:scale-95">
                <i class="fas fa-plus"></i> Thêm mới
            </button>
        </div>
    </div>
    <x-admin.product-add :categories="$categories" :brands="$brands" :tags="$tags" :colors="$colors" :sizes="$sizes" />
    <x-admin.product-edit :brands="$brands" :tags="$tags" :colors="$colors" :sizes="$sizes" />
    <x-admin.product-filter :categories="$categories" :brands="$brands" :tags="$tags" />
    <x-admin.product-variant-add :categories="$categories" :brands="$brands" :colors="$colors" :sizes="$sizes" />
    @if($products->isEmpty())
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã số</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nhãn hiệu</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trạng thái</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Tổng tồn kho</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right pr-12">Thao tác</th>
                </tr>
            </thead>
            @foreach ($products as $product)
            <tbody class="divide-y divide-gray-100" x-data="{ open: false }">
                <tr @click="open = !open" class=" group hover:bg-blue-50 border-blue-200 transition" :class="open ? 'bg-gray-50 border-gray-200' : 'bg-white'"
                    class="border-b transition-colors duration-300">
                    <td class="p-4 text-sm font-medium border-r text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $product->id }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('storage/'.$product->thumbnail) }}" class="w-10 h-14 object-cover rounded bg-gray-100">
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $product->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <p class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded border border-yellow-200">
                                    <i class="fas fa-star mr-1"></i> {{ $product->rating }}
                                </p>
                                <p class="text-sm text-gray-500">{{($product->reviews()->count())}}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $product->category->name }}
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $product->brand->name }}
                    </td>
                    <td class="p-4 text-center">
                        @if($product->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Không hoạt động
                        </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <span class="text-sm text-gray-600">{{ $product->variants->sum('quantity') }}</span>
                    </td>
                    <td class="p-4 text-right flex flex-row justify-center" @click.stop>
                        <button @click="$dispatch('open-variant-add-modal', @js($product))" class=" text-gray-400 hover:text-black p-2 transition"><i class="fas fa-plus fa-lg"></i></button>
                        <button @click="$dispatch('open-edit-modal', @js($product))" class=" text-gray-400 hover:text-black p-2 transition"><i class="fas fa-edit fa-lg"></i></button>
                        <form action="{{ route('admin.product.delete', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 p-2 transition" @click="if(!confirm('Bạn có chắc chắn muốn xóa?')) $event.preventDefault()">
                                <i class=" fas fa-trash fa-lg"></i>
                            </button>
                        </form>
                        <button @click="open = !open" class="text-gray-400 hover:text-black p-2 transition"><i class="fas fa-chevron-down fa-lg" :class="open ? 'rotate-180' : ''"></i></button>
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
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Màu sắc</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kích cỡ</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá gốc</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá giảm</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn kho</th>
                                            <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $variant)
                                        <tr class="border-b text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="border px-2 rounded-full bg-[{{$variant->color->hex_code}}] mr-1"></span>{{ $variant->color->name }}
                                            </td>
                                            <td>{{ $variant->size->name }}</td>
                                            <td><x-money :value="$variant->price" /></td>
                                            <td><x-money :value="$variant->sale_price" /></td>
                                            <td>{{ $variant->quantity }}</td>
                                            <td>
                                                @if($variant->status)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                                </span>
                                                @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Không hoạt động
                                                </span>
                                                @endif
                                            </td>
                                            <td class="p-4 flex justify-end">
                                                <form action="{{ route('admin.variant.delete', $variant) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 p-2 transition" @click="if(!confirm('Bạn có chắc chắn muốn xóa?')) $event.preventDefault()">
                                                        <i class=" fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
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
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('blur', function(e) {
        // Kiểm tra nếu là input type="number"
        if (e.target && e.target.type === 'number') {
            // Nếu giá trị trống hoặc không phải là số hợp lệ
            if (e.target.value === '') {
                e.target.value = 0;

                // Nếu bạn đang dùng Alpine.js, cần thông báo để nó cập nhật dữ liệu
                e.target.dispatchEvent(new Event('input'));
            }
        }
    }, true);
</script>
@endpush
