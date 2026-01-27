@props(['roles'])
<div x-data="{ isFilterOpen: {{ request()->anyFilled(['role', 'status']) ? 'true' : 'false' }} }">
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
                @if($roles)
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Chức vụ</label>
                    <select id="role-filter" name="role">
                        <option value="">Tất cả</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Giới tính</label>
                    <select id="gender-filter" name="status">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('gender') === '1'? 'selected' : '' }}>Nam giới</option>
                        <option value="0" {{ request('gender') === '0'? 'selected' : '' }}>Nữ giới</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Khả năng đánh giá</label>
                    <select id="review-filter" name="review">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('review') === '1'? 'selected' : '' }}>Có</option>
                        <option value="0" {{ request('review') === '0'? 'selected' : '' }}>Đã khóa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Trạng thái</label>
                    <select id="status-filter" name="status">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('status') === '1'? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') === '0'? 'selected' : '' }}>Đã khóa</option>
                    </select>
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
@once
@push('scripts')
<script>
    new TomSelect("#role-filter", {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    });
    new TomSelect("#gender-filter", {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    });
    new TomSelect("#status-filter", {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    });
    new TomSelect("#review-filter", {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    });
</script>
@endpush
@endonce
