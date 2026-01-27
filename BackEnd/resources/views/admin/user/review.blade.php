@extends('admin.layouts.app')
@section('title', 'Quản lý bình luận')
@section('subtitle', 'Duyệt và phản hồi bình luận sản phẩm')

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
@section('head-script')
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
<div class="flex-1 overflow-hidden p-6" x-data="reviewManager()">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-120px)]">

        <div class="lg:col-span-2 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden h-full">

            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 h-16 shrink-0" x-show="selected">
                <div class=" flex items-center gap-3">
                    <img :src="'{{ asset('storage') }}/' + selected.thumbnail" class=" w-10 h-10 rounded-lg object-cover border border-gray-200">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900" x-text="selected.name"></h2>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="text-yellow-500 mr-1"><i class="fas fa-star"></i></span>
                            <span x-text="selected.rating"></span>/5 •
                            <span x-text="selected.reviews_count" class="ml-1"></span> đánh giá
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 bg-gray-50/50">
                <div class="space-y-4">
                    <div x-show="loading" class="flex justify-center p-10 pt-[20%]">
                        <i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
                        <span class="ml-2">Đang tải đánh giá...</span>
                    </div>
                    <div x-show="!loading">
                        <template x-for="review in selected.reviews" :key="review.id">
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm transition hover:shadow-md mb-3">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                                            <img :src="'{{ asset('storage') }}/' + review.user.image" class="w-10 h-10 rounded-lg object-cover">
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900" x-text="review.user.name"></h4>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <div class="flex text-yellow-400 text-xs">
                                                    <template x-for="i in 5">
                                                        <i class="fas fa-star"
                                                            :class="i <= review.rating ? '' : 'text-gray-200'"></i>
                                                    </template>
                                                </div>
                                                <span class="text-xs text-gray-400" x-text="review.created_at"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="{
                                            'bg-green-100 text-green-800': review.status == 1,
                                            'bg-gray-100 text-red-800': review.status == 0
                                            }" x-text="review.status == 1 ? 'Đang hiển thị' : 'Đang ẩn'">
                                        </span>
                                    </div>
                                </div>
                                <p class=" text-sm text-gray-700 mb-3 ml-13" x-text="review.comment"></p>
                                <div class="flex items-center gap-3 ml-13 pt-3 border-t border-gray-100">
                                    <button @click="showReply[review.id] = !showReply[review.id]" class="text-xs font-medium text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                        <i class="fas fa-reply"></i> Trả lời
                                    </button>

                                    <template x-if="!review.status">
                                        <button @click="updateReview(review.id, 1)"
                                            :disabled="updatingId === review.id"
                                            class="text-xs font-medium text-green-600 hover:text-green-700 flex items-center gap-1 disabled:opacity-50">

                                            <i x-show="updatingId === review.id" class="fas fa-spinner fa-spin"></i>

                                            <i x-show="updatingId !== review.id" class="fas fa-check"></i>

                                            <span x-text="updatingId === review.id ? 'Đang xử lý...' : 'Hiển thị'"></span>
                                        </button>
                                    </template>

                                    <template x-if="review.status">
                                        <button @click="updateReview(review.id, 0)"
                                            :disabled="updatingId === review.id"
                                            class="text-xs font-medium text-red-600 hover:text-red-700 flex items-center gap-1 disabled:opacity-50">

                                            <i x-show="updatingId === review.id" class="fas fa-spinner fa-spin"></i>

                                            <i x-show="updatingId !== review.id" class="fas fa-x"></i>

                                            <span x-text="updatingId === review.id ? 'Đang xử lý...' : 'Ẩn đi'"></span>
                                        </button>
                                    </template>
                                    <button @click="deleteReview(review.id)" class="text-xs font-medium text-red-500 hover:text-red-700 flex items-center gap-1 ml-auto">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>

                                <div x-show="showReply[review.id]" x-transition class="mt-3 ml-13 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <i x-show="updatingReplyId === review.id" class="fas fa-spinner fa-spin"></i>
                                    <textarea x-model="review.reply" rows="2" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Nhập câu trả lời của admin..."></textarea>
                                    <div class="flex justify-end mt-2 gap-2">
                                        <button @click="showReply[review.id] = false" class="px-3 py-1.5 text-xs text-gray-600 font-medium hover:bg-gray-200 rounded-md">Đóng</button>
                                        <button @click="reply(review.id, review.reply)" class="px-3 py-1.5 text-xs bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700">Lưu trả lời</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div x-show="!selected" x-transition class="flex flex-col p-auto justify-center items-center text-gray-400 pt-[20%]">
                        <i class="fas fa-hand-point-right text-5xl mb-4"></i>
                        <h1 class="text-xl font-bold uppercase">Vui lòng chọn sản phẩm bên phải</h1>
                        <p class="text-sm">Danh sách bình luận sẽ hiển thị sau khi bạn chọn sản phẩm</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden h-full">

            <div class="p-4 border-b border-gray-200 bg-white shrink-0">
                <h3 class="font-bold text-gray-800 mb-3">Chọn sản phẩm</h3>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <form action="{{ url()->current() }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tìm tên SP, Mã SP...">
                    </form>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
                <div class="space-y-1">
                    @foreach($products as $product)
                    <div @click="select({{ $product }})"
                        class="flex items-center gap-3 p-3 rounded-lg cursor-pointer transition border border-transparent"
                        :class="selected && selected.id == {{ $product->id }} ? 'bg-blue-50 border-blue-200 ring-1 ring-blue-300' : 'hover:bg-gray-50 hover:border-gray-200'">
                        <div class="relative w-12 h-12 shrink-0">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" class="w-full h-full object-cover rounded-md border border-gray-100">
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs text-gray-500">Mã: <span>{{ $product->id }}</span></span>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <i class="fas fa-comment-alt text-gray-400 fa-lg"></i>
                                    <span>{{ $product->reviews_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function reviewManager() {
        return {
            selected: null,
            showReply: {},
            status: null,
            updatingId: null,
            loading: false,

            async deleteReview(reviewId) {
                if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) return;
                try {
                    const res = await axios.delete("{{ route('admin.user.deleteReview') }}", {
                        data: {
                            id: reviewId
                        }
                    });
                    this.selected.reviews = this.selected.reviews.filter(r => r.id !== reviewId);
                } catch (e) {
                    alert('Lỗi kết nối');
                }
            },

            async select(product) {
                this.selected = product;
                this.loading = true;
                try {
                    const res = await axios.get("{{ route('admin.user.getReviews') }}", {
                        params: {
                            id: product.id
                        }
                    });
                    this.selected.reviews = res.data.data;
                    console.log('log', this.selected.reviews);
                } catch (e) {
                    alert('Lỗi kết nối');
                } finally {
                    this.loading = false;
                }
            },

            async updateReview(reviewId, status) {
                this.updatingId = reviewId;
                try {
                    await axios.put("{{ route('admin.user.updateReview') }}", {
                        id: reviewId,
                        status: status
                    });
                    const review = this.selected.reviews.find(r => r.id == reviewId);
                    if (review) review.status = status;
                } catch (e) {
                    alert('Lỗi kết nối');
                } finally {
                    this.updatingId = null;
                }
            },

            async reply(reviewId, reply) {
                this.updatingReplyId = reviewId;
                try {
                    await axios.put("{{ route('admin.user.reply') }}", {
                        id: reviewId,
                        reply: reply
                    });
                    const review = this.selected.reviews.find(r => r.id == reviewId);
                    if (review) review.reply = reply;
                } catch (e) {
                    alert('Lỗi kết nối');
                } finally {
                    this.updatingReplyId = null;
                }
            },
        }
    }
</script>
@endpush
