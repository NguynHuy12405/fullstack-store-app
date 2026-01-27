@props(['product'])

<div
    x-data="{ show: false, rating: 0, hoverRating: 0 }"
    x-cloak
    @open-review-modal.window="show = true"
    @close-review-modal.window="show = false"
    @reset-rating.window="rating = 0; hoverRating = 0"
    class="relative z-50"
    x-show="show"
>
    <div x-show="show" x-transition class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="show = false"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
            <div x-show="show"
                x-transition
                class="relative transform overflow-hidden bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg rounded-lg"
            >
                <button @click="show = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="px-8 py-10">
                    <h3 class="text-2xl  font-bold text-gray-900 text-center mb-2">Đánh giá sản phẩm</h3>
                    <p class="text-xs text-gray-500 text-center mb-8">Chia sẻ trải nghiệm của bạn</p>

                    <div class="flex items-center gap-4 bg-gray-50 p-3 mb-8 rounded border border-gray-100">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="w-12 h-16 object-cover bg-white border">
                        <div>
                            <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">Mã SP: #{{ $product->id }}</p>
                        </div>
                    </div>

                    <form id="review-form" action="{{ route('user.review.store', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex flex-col items-center mb-6">
                            <p class="text-xs font-bold uppercase tracking-wider mb-2">Chất lượng sản phẩm</p>

                            <div class="flex gap-2" @mouseleave="hoverRating = 0">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                        @click="rating = {{ $i }}"
                                        @mouseenter="hoverRating = {{ $i }}"
                                        class="text-2xl focus:outline-none transition"
                                        :class="{
                                            'text-yellow-400 scale-110': hoverRating >= {{ $i }} || (rating >= {{ $i }} && hoverRating === 0),
                                            'text-gray-300': !(hoverRating >= {{ $i }} || (rating >= {{ $i }} && hoverRating === 0))
                                        }">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endfor
                            </div>

                            <input type="hidden" name="rating" :value="rating" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2">Nhận xét</label>
                            <textarea name="comment" rows="4"
                                class="w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-black focus:ring-0 text-sm p-4 resize-none rounded-sm"
                                placeholder="Hãy chia sẻ cảm nhận của bạn..." required></textarea>
                        </div>

                        <button type="submit"
                            :disabled="rating === 0"
                            :class="rating === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-black hover:bg-gray-800'"
                            class="w-full text-white py-4 text-sm font-bold uppercase tracking-widest rounded-sm transition">
                            Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('review-form');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const url = form.action;
            const formData = new FormData(form);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                // Validation lỗi từ FormRequest
                if (response.status === 422) {
                    const firstError = Object.values(data.errors)[0][0];
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dữ liệu không hợp lệ',
                        text: firstError,
                    });
                    return;
                }

                // Chưa mua/nhận hàng
                if (response.status === 400) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thông báo',
                        text: data.message,
                    });
                    return;
                }


                // Đã đánh giá rồi
                if (response.status === 409) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Thông báo',
                        text: data.message,
                    });
                    window.dispatchEvent(new CustomEvent('close-review-modal'));
                    return;
                }

                // Thành công
                if (response.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message,
                    });
                    form.reset();
                    window.dispatchEvent(new CustomEvent('reset-rating'));
                    window.dispatchEvent(new CustomEvent('close-review-modal'));
                    return;
                }

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi gửi dữ liệu.',
                });
            }
        });
    });
</script>
