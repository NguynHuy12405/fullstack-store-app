@extends('user.layouts.app')
@section('title','Trang liên hệ')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">

    <!-- Tiêu đề -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6 mt-16">
        📇 Thông tin liên hệ
    </h2>
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-4 text-left">Tên</th>
                    <th class="p-4 text-left">Liên kết</th>
                    <th class="p-4 text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($contacts as $contact)
                <tr class="hover:bg-gray-50">
                    <!-- Name -->
                    <td class="p-4 font-medium text-gray-800">
                        {{ $contact->name }}
                    </td>

                    <!-- URL -->
                    <td class="p-4">
                        @if($contact->url)
                        <a href="{{ $contact->url }}"
                            target="_blank"
                            class="text-blue-600 hover:underline break-all">
                            {{ $contact->url }}
                        </a>
                        @else
                        <span class="text-gray-400">Không có</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="p-4 text-center">
                        @if($contact->status == 1)
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            Đang hoạt động
                        </span>
                        @else
                        <span class="px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-600">
                            Không hoạt động
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-6 text-center text-gray-500">
                        Chưa có thông tin liên hệ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
