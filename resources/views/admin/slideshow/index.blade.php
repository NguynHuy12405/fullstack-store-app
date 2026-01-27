@extends('admin.layouts.app')
@section('title', 'Quản lý Đơn hàng')
@section('subtitle', '')

@section('content')
<div class="min-h-screen bg-gray-50 pt-8 pb-20 px-6 overflow-y-auto">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Danh sách Banner</h1>
        </div>
        <button onclick="openCreateModal()" 
            class="bg-black hover:bg-white hover:text-black text-white px-5 py-2.5 rounded-lg shadow-lg transition flex items-center gap-2"
        >
            <i class="fas fa-plus"></i> Thêm Slide
        </button>
    </div>

    {{-- LIST SLIDES --}}
    <div class="grid grid-cols-1 gap-4 ">
        @forelse($slides as $slide)
            <div class="group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all cursor-pointer"
                onclick='openEditModal(@json($slide))' 
            >
                <div class="flex flex-col sm:flex-row h-full">
                    {{-- Image --}}
                    <div class="w-full sm:w-48 h-32 sm:h-auto relative border-r border-gray-100">
                        <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-1 rounded text-white {{ $slide->status ? 'bg-green-500' : 'bg-gray-500' }}">
                            {{ $slide->status ? 'HIỂN THỊ' : 'ẨN' }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 p-5 relative">
                        <span class="absolute top-2 right-2 text-xs font-bold px-2 py-1 rounded text-white bg-green-500">{{ $slide->sort_order }}</span>
                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-700">{{ $slide->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-1">{{ $slide->description }}</p>
                        
                        {{-- Nút xóa --}}
                        <div class="flex justify-between items-center gap-3 mt-4 pt-3 border-t border-dashed border-gray-100">
                            <p class="text-sm text-gray-500 line-clamp-1"><span><i class="fa-solid fa-link mr-1"></i></span> {{ $slide->url }}</p>
                            {{-- Nút xóa --}}
                            <form action="{{ route('admin.slideshow.destroy', $slide) }}" method="POST" onsubmit="return confirm('Xóa slide này?');">
                                @csrf @method('DELETE')
                                <button onclick="event.stopPropagation()" class="text-xs text-red-500 hover:underline">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 py-10">Chưa có slide nào.</p>
        @endforelse

        <div>
            {{ $slides->links() }}
        </div>
    </div>

    {{-- MODAL (POPUP) --}}
    <x-admin.slide-modal />
</div>

@endsection