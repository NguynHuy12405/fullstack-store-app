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
@section('content')
@if (session('error'))
<div class="flex justify-center m-5">
    <div style="color: #721c24; padding: 10px; border: 1px solid #721c24; background: #f8d7da;">
        {{ session('error') }}
    </div>
</div>
@endif
@if (session('success'))
<div class="flex justify-center m-5">
    <div style="color: green; padding: 10px; border: 1px solid green; background: #e9f7ef;">
        {{ session('success') }}
    </div>
</div>
@endif

<div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.tag.index') }}">
                <button class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </button>
            </a>
            <x-admin.basic-filter />
        </div>
    </div>
    @if($tags->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 px-4">
        <div class="relative mb-6">
            <i class="fas fa-trash-alt text-gray-200 text-8xl"></i>
            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1">
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Thùng rác trống</h3>
        <p class="text-gray-500 text-center max-w-sm mb-8">
            Không tìm thấy hoặc chưa có gì cả!
        </p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã số</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên nhãn</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trạng thái</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right pr-12">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" x-data="{}">
                @foreach ($tags as $tag)
                <tr class="group hover:bg-blue-50 transition border-b transition-colors duration-300">
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $tag->id }}
                    </td>
                    <td class="p-4">
                        <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $tag->name }}</p>
                    </td>
                    <td class="p-4 text-center">
                        @if($tag->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Không hoạt động
                        </span>
                        @endif
                    </td>
                    <td class="p-4 flex justify-end gap-2">
                        <form action="{{ route('admin.tag.restore', $tag) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button class="text-green-500 hover:text-green-800 p-2 transition" @click="if(!confirm('Xác nhận khôi phục?')) $event.preventDefault()">
                                <i class="fas fa-rotate-left fa-lg"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.tag.forceDelete', $tag) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-800 p-2 transition" @click="if(!confirm('Bạn có chắc chắn muốn xóa?')) $event.preventDefault()">
                                <i class="fas fa-x fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            {{ $tags->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
