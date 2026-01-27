@props(['categories', 'brands', 'tags'])
<div x-data="{ isFilterOpen: {{ request()->anyFilled(['tags', 'brand', 'category', 'status']) ? 'true' : 'false' }} }">
    <template x-teleport="#filter-button">
        <button @click="isFilterOpen = !isFilterOpen" class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm" :class="isFilterOpen ? 'ring-2 ring-blue-500 border-blue-500 text-blue-600' : 'text-gray-700'">
            <i class="fas fa-filter mr-2"></i> Bộ lọc
        </button>
    </template>

    <div x-show="isFilterOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-inner"
        >
        <form id="filter-form" action="{{ url()->current() }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Từ khóa</label>
                    <select id="tag-filter" name="tags[]" multiple>
                        <option value="">Chọn nhiều từ khóa...</option>
                        @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, (array)request('tags')) ? 'selected' : '' }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Danh mục</label>
                    <select id="category-filter" name="category">
                        <option value="">Tất cả</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nhãn hiệu</label>
                    <select id="brand-filter" name="brand">
                        <option value="">Tất cả</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Trạng thái</label>
                    <select id="status-filter" name="status">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('status') === '1'? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') === '0'? 'selected' : '' }}>Không hoạt động</option>
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
    new TomSelect("#tag-filter", {
        create: false,
        render: {
            option: function(data, escape) {
                return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            ${escape(data.text)}
                            </span>`;
            }
        },
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            order: "asc"
        }
    });
    new TomSelect("#brand-filter", {
        create: false,
        sortField: {
            field: "text",
            order: "asc"
        }
    });
    new TomSelect("#category-filter", {
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
</script>
@endpush
@endonce
