@props(['colors', 'sizes'])
<div x-data="{ open: {{ $errors->variantAdd->any()? 'true' : 'false' }}, oldData: {{ $errors->variantAdd->any()? json_encode(old()) : json_encode((object)[]) }}, product: {} }"
    x-show="open"
    x-on:open-variant-add-modal.window="open = true; product = $event.detail"
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
                x-data="productVariantAddManager()"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white shadow-2xl rounded-lg flex relative max-h-[90vh] overflow-hidden transition-all">

                <div class="overflow-y-auto max-h-[79vh]">
                    <div class="flex-col overflow-y-auto max-w-2xl shrink-0">
                        <div class="flex justify-between items-center p-6 border-b">
                            <h3 class="text-lg font-bold mr-2">Thêm biến thể mới</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" action="{{ route('admin.variant.store') }}" x-ref="variantForm" class="space-y-6" @submit="handleSubmit($event)">
                                @csrf
                                @foreach ($errors->variantAdd->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Kích thước -->
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Kích cỡ</label>
                                            <span x-ref="sizeError" class="text-xs text-red-500 italic">*</span>
                                            <select x-ref="sizeSelect" name="size_id" class="cursor-pointer">
                                                <option value="" disabled selected hidden>Chọn kích thước...</option>
                                            </select>
                                        </div>
                                        <!-- Màu sắc -->
                                        <div>
                                            <label for="color-select" class="text-xs font-semibold uppercase text-gray-500">Màu sắc</label>
                                            <span x-ref="colorError" class="text-xs text-red-500 italic">*</span>
                                            <select x-ref="colorSelect" name="color_id" class="cursor-pointer uppercase">
                                                <option value="" disabled selected hidden>Chọn màu sắc...</option>
                                                @foreach ($colors as $color)
                                                <option value="{{$color->id}}" data-hex="{{$color->hex_code}}">{{$color->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Giá + Tồn kho -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Giá gốc</label>
                                            <input x-model.number="oldData.price" x-ref="inputPrice" name="price" type="number" class="w-full p-2.5 border rounded text-sm" min="0" step="1">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Giá đã giảm</label>
                                            <input x-model.number="oldData.sale_price" x-ref="inputSalePrice" name="sale_price" type="number" class="w-full p-2.5 border rounded text-sm" min="0" step="1">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Số lượng</label>
                                            <input x-model.number="oldData.quantity" x-ref="inputStock" name="quantity" type="number" class="w-full p-2.5 border rounded text-sm" min="0" step="1">
                                        </div>
                                    </div>
                                    <span class="mx-auto text-xs text-red-500 italic" x-ref="saleError"></span>
                                </div>

                                <!-- Trạng thái -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <span class="text-xs font-semibold uppercase text-gray-500">Trạng thái của biến thể</span>
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

                                <input type="hidden" name="product_id" x-model="oldData.product_id">
                                <input type="hidden" name="category_id" x-model="oldData.product_id">

                                <!-- Footer -->
                                <div class="flex justify-end gap-3 pt-3 border-t">
                                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Hủy</button>
                                    <button type="submit" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800">Lưu sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </template>
</div>
@once
@push('scripts')
<script>
    function productVariantAddManager() {
        return {
            async init() {
                const self = this;
                this.tsColor = new TomSelect(this.$refs.colorSelect, {
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const pick = this.dropdown.querySelector('#color-picker');
                            const data = {
                                name: input,
                                hex_code: pick.value,
                            }
                            if (data.hex_code === '#000000') {
                                const confirmBlack = confirm("Bạn đang chọn màu đen mặc định, tiếp tục?");
                                if (!confirmBlack) return callback(false);
                            }
                            const msg = self.$refs.colorError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.color.store') }}", data)
                                const result = res.data;
                                callback({
                                    value: result.data.id,
                                    text: result.data.name,
                                    hex: result.data.hex_code,
                                });
                                msg.className = 'text-xs text-green-500 italic'
                                msg.innerHTML = `Đã thêm "${result.data.name}" thành công!`;
                            } catch (error) {
                                callback(false);
                                if (error.response) {
                                    msg.className = 'text-xs text-red-500 italic';
                                    msg.innerHTML = error.response.data.message;
                                } else {
                                    console.log('Fetch error:', error);
                                    alert('Không thể kết nối đến server');
                                }
                            }
                        } else {
                            callback(false);
                        }
                    },
                    render: {
                        option_create: function(data, escape) {
                            return `
                            <div class="create flex items-center justify-between p-2">
                                <span>Thêm màu: <strong>${escape(data.input)}</strong></span>
                                <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                                    <input type="color" id="color-picker"
                                        class="color-picker-input w-6 h-6 border-none cursor-pointer">
                                    <span class="text-xs text-blue-600 font-bold">Chọn màu</span>
                                </div>
                            </div>`;
                        },
                        item: function(data, escape) {
                            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            <span class="w-4 h-4 rounded-full bg-[${escape(data.hex)}] mr-1 border"></span>
                            ${escape(data.text)}
                            </span>`;
                        },
                        option: function(data, escape) {
                            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            <span class="w-4 h-4 rounded-full bg-[${escape(data.hex)}] mr-1 border"></span>
                            ${escape(data.text)}
                            </span>`;
                        }
                    },
                    sortField: {
                        field: "text",
                        order: "asc",
                    },
                });

                this.tsSize = new TomSelect(this.$refs.sizeSelect, {
                    valueField: 'id',
                    labelField: 'name',
                    options: [],
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const data = {
                                name: input,
                                category_id: self.product.category_id,
                            }
                            const msg = self.$refs.sizeError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.size.store') }}", data)
                                const result = res.data;
                                callback({
                                    id: result.data.id,
                                    name: result.data.name,
                                });
                                msg.className = 'text-xs text-green-500 italic'
                                msg.innerHTML = `Đã thêm "${result.data.name}" thành công!`;
                            } catch (error) {
                                callback(false);
                                if (error.response) {
                                    msg.className = 'text-xs text-red-500 italic';
                                    msg.innerHTML = error.response.data.message;
                                } else {
                                    console.log('Fetch error:', error);
                                    alert('Không thể kết nối đến server');
                                }
                            }
                        } else {
                            callback(false);
                        }
                    },
                    render: {
                        option: function(data, escape) {
                            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                            ${escape(data.name)}
                            </span>`;
                        }
                    },
                    searchField: 'name',
                    sortField: {
                        field: "name",
                        order: "asc",
                    },
                });

                this.$watch('product', (product) => {
                    this.oldData.price = 0;
                    this.oldData.sale_price = 0;
                    this.oldData.quantity = 0;
                    this.oldData.status = 1;
                    this.oldData.category_id = String(product.category_id);
                    this.oldData.product_id = String(product.id);
                    this.loadSizesByCategory(this.oldData.category_id);
                })

                if (this.oldData) {
                    this.tsColor.setValue(String(this.oldData.color_id));
                    if (this.oldData.category_id) await this.loadSizesByCategory(this.oldData.category_id);
                }
            },

            async loadSizesByCategory(categoryId) {
                this.tsSize.clear();
                this.tsSize.clearOptions();
                const msg = this.$refs.sizeError;
                try {
                    msg.innerHTML = 'Đang tải...'
                    const res = await axios.get("{{ route('admin.category.size') }}", {
                        params: {
                            id: categoryId
                        }
                    })
                    msg.innerHTML = '*'
                    this.tsSize.addOptions(res.data);
                    this.tsSize.refreshOptions(false); //Refresh lại option để không cần reload lại trang
                } catch (error) {
                    if (error.response) {
                        msg.className = 'text-xs text-red-500 italic';
                        msg.innerHTML = error.response.data.message;
                    } else {
                        console.log('Fetch error:', error);
                        alert('Không thể kết nối đến server');
                    }
                }
                this.tsSize.setValue(String(this.oldData.size_id));
            },

            handleSubmit(e) {
                const color = this.$refs.colorSelect;
                const size = this.$refs.sizeSelect;

                const price = this.$refs.inputPrice;
                const salePrice = this.$refs.inputSalePrice;
                const quantity = this.$refs.inputQuantity;

                if (!color.value) {
                    e.preventDefault();
                    const colorMsg = this.$refs.colorError;
                    colorMsg.innerHTML = "Vui lòng chọn màu sắc!";
                    colorMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (!size.value) {
                    e.preventDefault();
                    const sizeMsg = this.$refs.sizeError;
                    sizeMsg.innerHTML = "Vui lòng chọn danh mục!";
                    sizeMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (Number(salePrice.value) > Number(price.value)) {
                    e.preventDefault();
                    const saleMsg = this.$refs.saleError;
                    saleMsg.innerHTML = "Giá giảm không được lớn hơn giá gốc!";
                }
            },
        }
    }
</script>
@endpush
@endonce
