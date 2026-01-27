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
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tổng tài khoản</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->total_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                <i class="fas fa-lock"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Đã khóa</p>
                <p class="text-2xl font-bold text-gray-900">{{ $counts->inactive_count }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-user"></i>
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
            <div class="relative w-full md:w-96">
                <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input form="filter-form" value="{{ request('search') }}" name="search" type="text" placeholder="Tìm kiếm theo ID, tên, email, sđt" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto" x-data="{}">
            <div id="unfill-button"></div>
            <div id="fill-button"></div>
            <div id="filter-button"></div>
        </div>
    </div>
    <x-admin.user-filter :roles="null" />
    @if($users->isEmpty())
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Người dùng</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giới tính</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khả năng đánh giá</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" x-data="{}">
                @foreach ($users as $user)
                <tr class="group hover:bg-blue-50 transition border-b transition-colors duration-300">
                    <td class="p-4 text-sm border-r font-medium text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 text-sm font-medium text-center">
                        {{ $user->id }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src=" {{ asset('storage/'.$user->image) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white/10 group-hover:ring-indigo-500/50 transition-all">
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-blue-600 transition">{{ $user->name }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $user->email }} - {{ $user->phone }}</span>
                        <p class="text-xs text-gray-500">{{ $user->address }}</p>
                    </td>
                    <td class="p-4 text-center">
                        @if($user->gender)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Nam
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-pink-50 text-pink-700 border border-pink-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span> Nữ
                        </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($user->review)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Có
                        </span>
                        <form method="POST" action="{{ route('admin.user.reviewLock', $user->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-500 text-white hover:bg-red-600 transition" @click="if(!confirm('Xác nhận khóa?')) $event.preventDefault()">Khóa</button>
                        </form>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Đã khóa
                        </span>
                        <form method="POST" action="{{ route('admin.user.reviewOpen', $user->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-green-500 text-white hover:bg-green-600 transition" @click="if(!confirm('Xác nhận mở khóa?')) $event.preventDefault()">Mở</button>
                        </form>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($user->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                        </span>
                        <form method="POST" action="{{ route('admin.user.statusLock', $user->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-500 text-white hover:bg-red-600 transition" @click="if(!confirm('Xác nhận mở khóa?')) $event.preventDefault()">Khóa</button>
                        </form>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Đã khóa
                        </span>
                        <form method="POST" action="{{ route('admin.user.statusOpen', $user->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-green-500 text-white hover:bg-green-600 transition" @click="if(!confirm('Xác nhận khóa?')) $event.preventDefault()">Mở</button>
                        </form>
                        @endif
                    </td>
                    <td class="p-4 text-sm font-medium">
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
