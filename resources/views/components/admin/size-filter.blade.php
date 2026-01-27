@props(['categories'])
<div class="relative w-full md:w-96">
    <form action="{{ url()->current() }}" class="flex items-center gap-3" x-data="{}">
        <div class="shrink-0">
            <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input @keydown.enter="$el.form.submit()" value="{{ request('search') }}" name="search" type="text" placeholder="Mã số, tên" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
        </div>
        <div class="shrink-0">
            <select id="category-filter" name="category" @change="$el.form.submit()">
                <option value="">Danh mục (Tất cả)</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="shrink-0">
            <select id="status-filter" name="status" @change="$el.form.submit()">
                <option value="">Trạng thái (Tất cả)</option>
                <option value="1" {{ request('status') === '1'? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0'? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>
    </form>
</div>
@once
@push('scripts')
<script>
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
