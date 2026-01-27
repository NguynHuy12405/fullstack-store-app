<div x-data="{ open: {{ $errors->edit->any()? 'true' : 'false' }}, oldData: {{ $errors->edit->any()? json_encode(old()) : json_encode((object)['status' => 1]) }}, size: {} }"
    x-show="open"
    x-on:open-edit-modal.window="open = true; size = $event.detail"
    style="display: none;"
    class="fixed inset-0 z-[9999] overflow-y-auto">

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

            <div x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm">
            </div>

            <div x-show="open"
                x-data="sizeEditManager()"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white shadow-2xl rounded-lg flex relative w-2/5 max-h-[90vh] overflow-hidden transition-all">

                <div class="overflow-y-auto max-h-[79vh] flex-grow">
                    <div class="flex-col overflow-y-auto max-w-2xl">
                        <div class="flex justify-between items-center p-6 border-b">
                            <h3 class="text-lg font-bold mr-2">Sửa kích cỡ</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" :action="'{{ route('admin.size.update', ['size' => ':id']) }}'.replace(':id', oldData.id)" x-ref="sizeForm" class="space-y-6" @submit="handleSubmit($event)">
                                @method('PUT')
                                @csrf
                                @foreach ($errors->edit->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Tên kích cỡ</label>
                                            <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                            <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên kích cỡ" maxlength="255">
                                        </div>
                                        <!-- Danh mục -->
                                        <div>
                                            <div class="mb-[3px]">
                                                <label class="text-xs font-semibold uppercase text-gray-500">Danh mục</label>
                                                <span x-ref="categoryError" class="text-xs text-red-500 italic">*</span>
                                            </div>
                                            <select x-ref="categorySelect" name="category_id" x-model="oldData.category_id" class="cursor-pointer">
                                                <option value="" disabled selected hidden>Chọn danh mục...</option>
                                                @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Trạng thái -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <span class="text-xs font-semibold uppercase text-gray-500">Trạng thái</span>
                                        <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" value="1" class="peer hidden" x-model="oldData.status">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                                    Hoạt động
                                                </span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" value="0" class="peer hidden" x-model="oldData.status">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-red-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>
                                                    Tạm ẩn
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Footer -->
                                <div class="flex justify-end gap-3 pt-3 border-t">
                                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Hủy</button>
                                    <button type="submit" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800">Lưu</button>
                                </div>
                                <input type="hidden" name="id" x-model="oldData.id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@push('scripts')
<script>
    function sizeEditManager() {
        return {
            init() {
                const self = this;
                this.tsCategory = new TomSelect(this.$refs.categorySelect, {
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const data = {
                                name: input
                            };
                            const msg = self.$refs.categoryError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.category.store') }}", data)
                                const result = res.data;
                                callback({
                                    value: result.data.id,
                                    text: result.data.name,
                                });
                                msg.className = 'text-xs text-green-500 italic'
                                msg.innerHTML = `Đã thêm "${result.data.name}" thành công!`;
                            } catch (error) {
                                callback(false);
                                if (error.response) {
                                    msg.className = 'text-xs text-red-500 italic';
                                    msg.innerHTML = Object.values(error.response.data.errors).flat()[0];
                                } else {
                                    console.log('Fetch error:', error);
                                    alert('Không thể kết nối đến server');
                                }
                            }
                        } else {
                            callback(false);
                        }
                    },
                    sortField: {
                        field: "text",
                        order: "asc"
                    }
                });

                this.$watch('size', (size) => {
                    this.oldData.id = String(size.id);
                    this.oldData.name = String(size.name);
                    this.oldData.category_id = String(size.category_id);
                    this.oldData.status = String(size.status);
                    this.tsCategory.setValue(String(size.category_id));
                })
                if (this.oldData) {
                    this.tsCategory.setValue(String(this.oldData.category_id));
                }
            },

            handleSubmit(e) {
                //Kiểm tra trước khi submit
                const name = this.$refs.nameInput;
                const category = this.$refs.categorySelect;

                if (!category.value) {
                    e.preventDefault();
                    const categoryMsg = this.$refs.categoryError;
                    categoryMsg.innerHTML = "Vui lòng chọn danh mục!";
                }

                if (!name.value) {
                    e.preventDefault();
                    const nameMsg = this.$refs.nameError;
                    nameMsg.innerHTML = "Không được để trống!";
                    if (!hasError) {
                        nameMsg.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        })
                    }
                }
            }
        }
    }
</script>
@endpush
