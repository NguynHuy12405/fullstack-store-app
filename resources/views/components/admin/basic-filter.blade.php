<div class="relative w-full md:w-96">
    <form action="{{ url()->current() }}" class="flex items-center gap-3" x-data="{}">
        <div>
            <i class="fas fa-search fa-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input value="{{ request('search') }}" name="search" type="text" placeholder="Tìm kiếm mã số, tên" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition shadow-sm">
        </div>
        <div>
            <select id="status-filter" name="status" @change="$el.form.submit()" class="h-[42px] w-full pl-4 pr-10 py-2 bg-white border border-gray-200 rounded-xl text-sm
           appearance-none cursor-pointer
           focus:outline-none focus:ring-4 focus:ring-black/5 focus:border-black
           transition-all shadow-sm">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') === '1'? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0'? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>
    </form>
</div>
