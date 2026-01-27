@extends('user.layouts.app')
@section('title','Trang review')

@section('content')
<main class="pt-20 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            ⭐ Đánh giá sản phẩm của tôi
        </h2>

        @forelse($reviews as $review)
            <div class="bg-white rounded-2xl shadow p-6 mb-6">

                <!-- Header -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $review->product->name ?? 'Sản phẩm đã xóa' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            Đánh giá ngày {{ $review->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    <!-- Rating -->
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.538 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.783.57-1.838-.197-1.538-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.049 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.974z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                <!-- Comment -->
                <p class="text-gray-700 leading-relaxed mb-4">
                    {{ $review->comment ?? 'Không có nội dung đánh giá' }}
                </p>

                <!-- Reply from admin -->
                @if($review->reply)
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-blue-700 mb-1">
                            Phản hồi từ cửa hàng
                        </p>
                        <p class="text-gray-700">
                            {{ $review->reply }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ optional($review->reply_at)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @endif

            </div>
        @empty
            <div class="bg-white rounded-xl p-10 text-center text-gray-500">
                Bạn chưa đánh giá sản phẩm nào
            </div>
        @endforelse

    </div>
</main>

@endsection
