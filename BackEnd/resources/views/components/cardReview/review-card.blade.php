@props(['review'])

<div class="border-b border-gray-100 pb-8 last:border-0 last:pb-0">
    <div class="flex justify-between items-start mb-4">
        <div class="flex gap-4">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center  font-bold text-gray-600 uppercase">
                {{ strtoupper(substr(optional($review->user)->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">{{ optional($review->user)->name ?? 'Người dùng ẩn danh' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    {{-- Render số sao của user --}}
                    <div class="flex text-yellow-500 text-xs">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">• {{ $review->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <p class="text-gray-600 text-sm leading-relaxed mb-4">
        {{ $review->comment }}
    </p>

    {{-- Admin Reply --}}
    <div class="relative">
        @if($review->is_replied)
            <div class="mt-5 pl-6 sm:pl-10 relative">
                <div class="absolute left-0 top-0 bottom-0 w-px bg-gray-200"></div>
                <div class="absolute left-0 top-4 w-4 sm:w-8 h-px bg-gray-200"></div>

                <div class="bg-gray-50 rounded-lg p-4 sm:p-5 border border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                                {{ strtoupper(substr(optional($review->replierUser)->name ?? 'A', 0, 1)) }}
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-gray-900">
                                        {{ optional($review->replierUser)->name ?? 'Administrator' }}
                                    </h4>

                                    {{-- Badge Role --}}
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-200 text-gray-600">
                                        {{ optional(optional($review->replierUser)->role)->name ?? 'Administrator' }}
                                    </span>
                                </div>

                                @if ($review->reply_at)
                                    <p class="text-xs text-gray-400">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($review->reply_at)->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>  
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 leading-relaxed">
                        {!! nl2br(e($review->reply)) !!}
                    </div>
                </div>
            </div>
        @endif
        @can('reply', $review)
            @if(!$review->is_replied)
            <div class="mt-5 pl-6 sm:pl-10 relative" x-data="{ focused: false }">
                {{-- Decorative Line --}}
                <div class="absolute left-0 top-0 bottom-0 w-px bg-gray-200"></div>
                <div class="absolute left-0 top-6 w-4 sm:w-8 h-px bg-gray-200"></div>

                <div class="bg-white rounded-lg border transition-all duration-300 overflow-hidden shadow-sm"
                    :class="focused ? 'border-black ring-1 ring-black/5 shadow-md' : 'border-gray-200'">
                    
                    <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                        @csrf
                        
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                                <i class="fas fa-reply mr-1"></i> Phản hồi khách hàng
                            </span>
                        </div>

                        <div class="px-2 pt-2 pb-0">
                            <textarea
                                name="reply"
                                rows="3"
                                class="w-full border-none p-2 text-sm text-gray-700 placeholder-gray-400 focus:ring-0 resize-none bg-transparent"
                                placeholder="Nhập nội dung phản hồi của cửa hàng..."
                                required
                                @focus="focused = true"
                                @blur="focused = false"
                            ></textarea>
                        </div>

                        <div class="px-4 py-2 bg-white flex justify-between items-center border-t border-gray-50">
                            @error('reply')
                                <p class="text-xs text-red-500 font-medium"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @else
                                <span></span>
                            @enderror

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-black text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-gray-800 transition duration-150 ease-in-out gap-2">
                                <span>Gửi phản hồi</span>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @endcan
    </div>
</div>