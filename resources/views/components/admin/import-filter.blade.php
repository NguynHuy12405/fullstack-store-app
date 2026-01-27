<div x-data="{ isFilterOpen: {{ request()->anyFilled(['min_price', 'max_price', 'from_date', 'to_date']) ? 'true' : 'false' }} }">
    <template x-teleport="#filter-button">
        <button @click="isFilterOpen = !isFilterOpen" class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm" :class="isFilterOpen ? 'ring-2 ring-blue-500 border-blue-500 text-blue-600' : 'text-gray-700'">
            <i class="fas fa-filter mr-2"></i> Bộ lọc
        </button>
    </template>

    <div x-show="isFilterOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-inner">
        <form id="filter-form" action="{{ url()->current() }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                <div class="flex flex-col text-left">
                    <label class="text-xs font-semibold text-gray-500 mb-1">GIÁ TỪ</label>
                    <input type="number" name="min_price" placeholder="Thấp nhất" value="{{ request('min_price') }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 border-gray-200">
                </div>

                <div class="flex flex-col text-left">
                    <label class="text-xs font-semibold text-gray-500 mb-1">ĐẾN GIÁ</label>
                    <input type="number" name="max_price" placeholder="Cao nhất" value="{{ request('max_price') }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 border-gray-200">
                </div>

                <div class="flex flex-col text-left">
                    <label class="text-xs font-semibold text-gray-500 mb-1">TỪ NGÀY</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 border-gray-200">
                </div>

                <div class="flex flex-col text-left">
                    <label class="text-xs font-semibold text-gray-500 mb-1">ĐẾN NGÀY</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 border-gray-200">
                </div>

                <div class="flex gap-4 items-center mt-3">
                    <a href="{{ url()->current() }}">
                        <button type="button" class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm">Xóa lọc</button>
                    </a>
                    <button type="submit" class="px-4 py-2.5 bg-blue-500 text-white rounded-lg text-sm font-bold hover:bg-blue-800 transition-transform active:scale-95">Áp dụng</button>
                </div>
            </div>
        </form>
    </div>
</div>
