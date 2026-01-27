@extends('admin.layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('link')
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
                <i class="fas fa-list"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng màu sắc</p>
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
            <a href="{{ route('admin.color.trash') }}">
                <button class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class="fas fa-trash mr-2"></i> Thùng rác
                </button>
            </a>
            <x-admin.basic-filter />
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
    <x-admin.color-add />
    <x-admin.color-edit />
    @if($colors->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 px-4">
        <div class="relative mb-6">
            <i class="fas fa-search-minus text-gray-200 text-8xl"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Không tìm thấy</h3>
        <p class="text-gray-500 text-center max-w-sm mb-8">
            Hãy thử tìm lại với tham số khác, hoặc thêm mới!
        </p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200 text-center">
                <tr>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã số</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên màu sắc</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-center" x-data="{}">
                @foreach ($colors as $color)
                <tr class="group hover:bg-blue-50 transition border-b transition-colors duration-300">
                    <td class="p-4 text-sm border-r font-medium text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $color->id }}
                    </td>
                    <td class="p-4 text-left">
                        <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">
                            <span class="border px-2.5 rounded-full bg-[{{$color->hex_code}}] mr-1"></span>{{ $color->name }}
                        </p>
                    </td>
                    <td class="p-4 text-center">
                        @if($color->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Không hoạt động
                        </span>
                        @endif
                    </td>
                    <td class="p-4 flex flex-row justify-center gap-2">
                        <button @click="$dispatch('open-edit-modal', @js($color))" class=" text-gray-400 hover:text-black p-2 transition"><i class="fas fa-edit fa-lg"></i></button>
                        <form action="{{ route('admin.color.delete', $color) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 p-2 transition" @click="if(!confirm('Bạn có chắc chắn muốn xóa?')) $event.preventDefault()">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            {{ $colors->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
