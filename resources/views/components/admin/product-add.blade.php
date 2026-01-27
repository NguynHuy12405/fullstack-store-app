@props(['categories', 'brands', 'tags', 'colors', 'sizes'])
<div x-data="{ open: {{ $errors->add->any()? 'true' : 'false' }}, oldData: {{ $errors->add->any()? json_encode(old()) : json_encode((object)[]) }} }"
    x-show="open"
    x-on:open-add-modal.window="open = true"
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
                x-data="productAddManager()"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white w-full shadow-2xl rounded-lg flex relative max-h-[90vh] overflow-hidden transition-all"
                :class="isExpanded ? 'max-w-7xl' : 'max-w-2xl'">


                <div class="overflow-y-auto max-h-[79vh]">
                    <div class="flex-col overflow-y-auto max-w-2xl shrink-0">
                        <div class="flex justify-between items-center p-6 border-b">
                            <h3 class="text-lg font-bold mr-2">Thêm sản phẩm mới</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" action="{{ route('admin.product.store') }}" x-ref="productForm" class="space-y-6" enctype="multipart/form-data" @submit="handleSubmit($event)">
                                @csrf
                                @foreach ($errors->add->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <!-- Tên -->
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Tên sản phẩm</label>
                                        <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                        <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên sản phẩm" maxlength="255">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Danh mục -->
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Danh mục</label>
                                        <span x-ref="categoryError" class="text-xs text-red-500 italic">*</span>
                                        <select x-ref="categorySelect" name="category_id" class="cursor-pointer">
                                            <option value="" disabled selected hidden>Chọn danh mục...</option>
                                            @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Nhãn hiệu -->
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Nhãn hiệu</label>
                                        <span x-ref="brandError" class="text-xs text-red-500 italic">*</span>
                                        <select x-ref="brandSelect" name="brand_id" class="cursor-pointer">
                                            <option value="" disabled selected hidden>Chọn nhãn hiệu...</option>
                                            @foreach ($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Kích thước -->
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Kích cỡ</label>
                                            <span x-ref="sizeError" class="text-xs text-red-500 italic">Vui lòng chọn danh mục trước!</span>
                                            <select x-ref="sizeSelect" name="sizes[]" class="cursor-pointer" multiple>
                                                <option value="" disabled selected hidden>Chọn nhiều kích thước...</option>
                                            </select>
                                        </div>
                                        <!-- Màu sắc -->
                                        <div>
                                            <label for="color-select" class="text-xs font-semibold uppercase text-gray-500">Màu sắc</label>
                                            <span x-ref="colorError" class="text-xs text-red-500 italic"></span>
                                            <select x-ref="colorSelect" name="colors[]" class="cursor-pointer uppercase" multiple>
                                                <option value="" disabled selected hidden>Chọn nhiều màu sắc...</option>
                                                @foreach ($colors as $color)
                                                <option value="{{$color->id}}" data-hex="{{$color->hex_code}}" {{ (is_array(old('colors')) && in_array($color->id, old('colors'))) ? 'selected' : '' }}>{{$color->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Giá + Tồn kho -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Giá gốc</label>
                                            <input x-ref="inputPrice" type="number" class="w-full p-2.5 border rounded text-sm" placeholder="0" min="0" step="1">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Giá đã giảm</label>
                                            <input x-ref="inputSalePrice" type="number" class="w-full p-2.5 border rounded text-sm" placeholder="0" min="0" step="1">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Số lượng</label>
                                            <input x-ref="inputStock" type="number" class="w-full p-2.5 border rounded text-sm" placeholder="0" min="0" step="1">
                                        </div>
                                    </div>
                                    <div class="text-center mt-5 gap-15">
                                        <button @click="isExpanded = !isExpanded" type="button" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Ẩn/Hiện danh sách</button>
                                        <button @click="handleEdit()" type="button" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800">Sửa nhanh</button>
                                        <button @click="handleGenerateClick()" type="button" class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-800">Tạo biến thể</button>
                                    </div>
                                    <span x-ref="generateError" class="mx-auto italic text-red-500 text-xs mt-2"></span>
                                </div>

                                <!-- Trạng thái -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <span class="text-xs font-semibold uppercase text-gray-500">Trạng thái của sản phẩm</span>
                                        <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" value="1" class="peer hidden" checked>
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                                    Hoạt động
                                                </span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status" value="0" class="peer hidden">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-red-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>
                                                    Tạm ẩn
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload ảnh -->
                                <div>
                                    <label class="text-xs font-semibold uppercase text-gray-500">Hình ảnh</label>
                                    <span x-ref="imageError" class="text-xs text-red-500 italic"></span>
                                    <div>
                                        <div x-ref="cropArea" class="hidden mx-auto ">
                                            <div x-ref="uploadCrop"></div>
                                            <div class="flex justify-center gap-1">
                                                <button type="button" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200" x-ref="cropCancelButton">Hủy</button>
                                                <button type="button" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800" x-ref="cropButton">Cắt ảnh</button>
                                            </div>
                                        </div>
                                        <div x-ref="imageArea" class="max-w-4xl mx-auto p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[400px]">

                                                <div class="md:col-span-2 relative group overflow-hidden rounded-2xl shadow-lg">
                                                    <div x-ref="image-1" class="flex items-center justify-center w-full h-full">
                                                        <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                <p class="mb-2 text-sm text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                <p class="text-xs text-gray-400 font-medium">PNG, JPG hoặc JPEG (Tỉ lệ 3:4)</p>
                                                                <p class="text-xs text-gray-400 font-medium">Tối đa: 2MB</p>
                                                            </div>
                                                            <input x-ref="upload-1" data-index="1" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                        </label>
                                                    </div>
                                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" x-ref="preview-1" src="" style="display:none;">
                                                    <button type="button" onclick="removeImage(1)" x-ref="btn-delete-1"
                                                        class="hidden absolute top-2 right-2 z-20 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-red-600 transition shadow-lg">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                    <div class="absolute bottom-4 left-4 bg-white/80 backdrop-blur px-3 py-1 rounded-full text-sm font-semibold shadow-sm">
                                                        Ảnh chính
                                                    </div>
                                                </div>

                                                <div class="md:col-span-2 grid grid-cols-4 md:grid-cols-2 gap-3 h-full">
                                                    <div class="relative rounded-xl overflow-hidden cursor-pointer shadow-md">
                                                        <div x-ref="image-2" class="flex items-center justify-center w-full h-full">
                                                            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                    <p class="mb-2 text-xs text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                </div>
                                                                <input x-ref="upload-2" data-index="2" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                            </label>
                                                        </div>
                                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" x-ref="preview-2" src="" style="display:none;">
                                                        <button type="button" onclick="removeImage(2)" x-ref="btn-delete-2"
                                                            class="hidden absolute top-2 right-2 z-20 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-red-600 transition shadow-lg">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </div>
                                                    <div class="relative rounded-xl overflow-hidden cursor-pointer shadow-md">
                                                        <div x-ref="image-3" class="flex items-center justify-center w-full h-full">
                                                            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                    <p class="mb-2 text-xs text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                </div>
                                                                <input x-ref="upload-3" data-index="3" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                            </label>
                                                        </div>
                                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" x-ref="preview-3" src="" style="display:none;">
                                                        <button type="button" onclick="removeImage(3)" x-ref="btn-delete-3"
                                                            class="hidden absolute top-2 right-2 z-20 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-red-600 transition shadow-lg">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </div>
                                                    <div class="relative rounded-xl overflow-hidden cursor-pointer shadow-md">
                                                        <div x-ref="image-4" class="flex items-center justify-center w-full h-full">
                                                            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                    <p class="mb-2 text-xs text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                </div>
                                                                <input x-ref="upload-4" data-index="4" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                            </label>
                                                        </div>
                                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" x-ref="preview-4" src="" style="display:none;">
                                                        <button type="button" onclick="removeImage(4)" x-ref="btn-delete-4"
                                                            class="hidden absolute top-2 right-2 z-20 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-red-600 transition shadow-lg">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </div>
                                                    <div class="relative rounded-xl overflow-hidden cursor-pointer shadow-md">
                                                        <div x-ref="image-5" class="flex items-center justify-center w-full h-full">
                                                            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                    <p class="mb-2 text-xs text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                </div>
                                                                <input x-ref="upload-5" data-index="5" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                            </label>
                                                        </div>
                                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" x-ref="preview-5" src="" style="display:none;">
                                                        <button type="button" onclick="removeImage(5)" x-ref="btn-delete-5"
                                                            class="hidden absolute top-2 right-2 z-20 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-red-600 transition shadow-lg">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nhãn -->
                                <div>
                                    <label for="tag-select" class="text-xs font-semibold uppercase text-gray-500">Nhãn</label>
                                    <span x-ref="tag-error" class="text-xs text-red-500 italic"></span>
                                    <select x-ref="tagSelect" name="tags[]" multiple>
                                        <option value="" disabled selected hidden>Chọn nhiều nhãn...</option>
                                        @foreach ($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Mô tả -->
                                <div>
                                    <label class="text-xs font-semibold uppercase text-gray-500">Mô tả</label>
                                    <textarea x-model="oldData.description" rows="3" name="description" class="w-full p-2.5 border rounded text-sm" placeholder="Mô tả ngắn sản phẩm..."></textarea>
                                </div>

                                <!-- Footer -->
                                <div class="flex justify-end gap-3 pt-3 border-t">
                                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Hủy</button>
                                    <button type="submit" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800">Lưu sản phẩm</button>
                                </div>
                                <input type="hidden" x-ref="variantsInput" name="variants_data" :value="JSON.stringify(variants)">
                                <input type="file" x-ref="thumbnailInput" name="cropped-thumbnail" class="hidden">
                                <input type="file" x-ref="imagesInput" name="cropped-images[]" class="hidden" multiple>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-y-auto flex-col flex-1">
                    <div class="w-full h-full p-6 overflow-y-auto bg-gray-50 transition-all duration-500"
                        :class="isExpanded ? 'opacity-100' : 'w-0 opacity-0'">
                        <div class="flex justify-between items-center p-6 border-b bg-white rounded-tl-xl rounded-tr-xl">
                            <h3 class="text-xl font-bold">Chi tiết các biến thể</h3>
                        </div>
                        <table class="w-full text-sm bg-white rounded-xl shadow-xl">
                            <thead class="bg-blue-50 border-b border-gray-200">
                                <tr class="text-center">
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Màu sắc</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kích cỡ</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá gốc</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Giá giảm</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tồn kho</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hoạt động</th>
                                    <th class="p-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(variant, index) in variants" :key="variant.id">
                                    <tr class="border-b text-center">
                                        <td x-text="index + 1">
                                        </td>
                                        <td>
                                            <span class="px-2 rounded-full mr-1 border" :style="`background-color: ${getHex(variant.color_id)}`"></span>
                                        </td>
                                        <td class="p-2">
                                            <span x-text="variant.size.name"></span>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" x-model.number="variant.price" min="0"
                                                class=" w-full border-0 focus:ring-2 focus:ring-blue-400 rounded bg-transparent hover:bg-white p-1 uppercase">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" x-model.number="variant.sale_price" min="0" @blur="if(variant.sale_price > variant.price) variant.sale_price = variant.price;"
                                                class=" w-full border-0 focus:ring-2 focus:ring-blue-400 rounded bg-transparent hover:bg-white p-1 uppercase">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" x-model.number="variant.quantity" min="0"
                                                class="w-full border-0 focus:ring-2 focus:ring-blue-400 rounded bg-transparent hover:bg-white p-1 uppercase">
                                        </td>
                                        <td class="p-2">
                                            <input type="checkbox" x-model="variant.status">
                                        </td>
                                        <td class="p-3 text-center">
                                            <button @click="removeRow(index)" class="text-red-500 font-bold">Xóa</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@push('scripts')
<script>
    function productAddManager() {
        return {
            isExpanded: false,
            variants: [],
            colors: @json(old("colors", "[]")),
            sizes: @json(old("sizes", "[]")),
            colorMap: JSON.parse('@json($colors -> pluck("hex_code", "id") ?? [])'),

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
                    },
                    onChange: async (value) => {
                        if (!value) {
                            self.$refs.sizeError.innerHTML = "Vui lòng chọn danh mục!";
                            return
                        };
                        self.variants = [];
                        await self.loadSizesByCategory(value);
                    }
                });

                this.tsBrand = new TomSelect(this.$refs.brandSelect, {
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const data = {
                                name: input
                            };
                            const msg = self.$refs.brandError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.brand.store') }}", data)
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
                                self.colorMap[result.data.id] = result.data.hex_code;
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
                    onChange: (val) => {
                        this.colors = val;
                    },
                    plugins: ['remove_button'],
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
                                category_id: self.$refs.categorySelect.value,
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
                    onChange: (val) => {
                        this.sizes = val.map(id => {
                            const data = this.tsSize.options[id];
                            return {
                                id: id,
                                name: data.name,
                            };
                        });
                    },
                    plugins: ['remove_button'],
                    searchField: 'name',
                    sortField: {
                        field: "name",
                        order: "asc",
                    },
                });

                this.tsTag = new TomSelect(this.$refs.tagSelect, {
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const data = {
                                name: input
                            };
                            const msg = self.$refs.tagError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.tag.store') }}", data)
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
                                    msg.innerHTML = Object.values(error?.response?.data?.errors).flat()[0];
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

                this.$nextTick(() => {
                    if (this.oldData) {
                        this.tsCategory.setValue(String(this.oldData.category_id));
                        this.tsBrand.setValue(String(this.oldData.brand_id));
                        this.tsTag.setValue(Object.values(this.oldData.tags || {}).map(t => String(t)));
                        this.variants = JSON.parse(this.oldData.variants_data || '[]');
                    }
                })

                if (this.tsCategory.getValue()) {
                    this.$refs.sizeError.innerHTML = '';
                    this.loadSizesByCategory(this.tsCategory.getValue());
                }
                window.croppedAddImages = {};
                var el = this.$refs.uploadCrop;
                if (el) {
                    this.uploadCrop = new Croppie(el, {
                        viewport: {
                            width: 300,
                            height: 400,
                            type: 'square'
                        }, // Khung cắt
                        boundary: {
                            width: 500,
                            height: 400,
                        }, // Vùng bao ngoài
                        showZoomer: true, // Hiện thanh trượt zoom
                    });
                }
                // Khi nhấn nút hủy cắt ảnh
                this.$refs.cropCancelButton.addEventListener('click', () => {
                    const targetIndex = event.target.getAttribute('data-target');
                    if (confirm('Xác nhận hủy?')) {
                        //xóa input
                        this.$refs['upload-' + targetIndex].value = "";
                        //Ẩn vùng cắt ảnh
                        this.$refs.cropArea.classList.add('hidden');
                        //Hiển thị vùng thêm ảnh
                        this.$refs.imageArea.classList.remove('hidden');
                    }
                });
                // 3. Khi nhấn nút "Cắt ảnh"
                this.$refs.cropButton.addEventListener('click', () => {
                    // 1. Lấy index (1, 2, 3...) mà input đang xử lý
                    const targetIndex = event.target.getAttribute('data-target');

                    this.uploadCrop.result({
                        type: 'blob',
                        size: {
                            width: 1200,
                            height: 1600
                        } // Tỉ lệ 3:4
                    }).then((blob) => {
                        // 2. Hiển thị xem trước vào đúng ô
                        const previewElement = this.$refs['preview-' + targetIndex];

                        if (previewElement) {
                            var url = URL.createObjectURL(blob);
                            previewElement.src = url;
                            previewElement.style.display = 'block';
                            //Ẩn vùng thêm ảnh
                            this.$refs['image-' + targetIndex].style.display = 'none';
                            //Hiển thị nút xóa
                            this.$refs['btn-delete-' + targetIndex].classList.remove('hidden');
                            //Hiển thị vùng thêm ảnh
                            this.$refs.imageArea.classList.remove('hidden');
                            if (targetIndex == 1) {
                                this.$refs.imageError.innerHTML = '';
                            }
                        }
                        // 3. Lưu blob vào mảng toàn cục theo Index
                        window.croppedAddImages[targetIndex] = blob;

                        //Ẩn vùng cắt ảnh
                        this.$refs.cropArea.classList.add('hidden');
                    });
                });
                // Nút xóa ảnh
                window.removeImage = (index) => {
                    if (confirm('Xóa ảnh này?')) {
                        this.$refs['preview-' + index].classList.add('hidden');
                        this.$refs['btn-delete-' + index].classList.add('hidden');
                        this.$refs['upload-' + index].value = ""; // Reset input
                        this.$refs['preview-' + index].src = ""; // Reset image
                        this.$refs['image-' + index].style.display = 'block'; //Hiện vùng thêm ảnh
                        delete window.croppedAddImages[index];
                    }
                };

                this.$refs.productForm.addEventListener('keydown', (e) => {
                    // Kiểm tra xem phần tử đang gõ có phải là input number không
                    if (e.target.type === 'number') {
                        if (['.', ',', 'e', 'E', '-'].includes(e.key)) {
                            e.preventDefault();
                        }
                    }
                });
            },

            handleSubmit(e) {
                //Lấy json từ bảng biến thể
                const variantsRaw = this.$refs.variantsInput.value;

                //Chia hình ảnh thumbnail và hình ảnh con
                const thumbnail = new DataTransfer();
                const images = new DataTransfer();

                //Kiểm tra trước khi submit
                const name = this.$refs.nameInput;
                const brand = this.$refs.brandSelect;
                const category = this.$refs.categorySelect;

                // Kiểm tra rỗng
                if (!variantsRaw || variantsRaw === '[]') {
                    alert('Danh sách biến thể không được để trống!');
                    e.preventDefault(); // Dừng submit lại
                }

                if (!name.value) {
                    e.preventDefault();
                    const nameMsg = this.$refs.nameError;
                    nameMsg.innerHTML = "Tên sản phẩm không được để trống!";
                    nameMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (!brand.value) {
                    e.preventDefault();
                    const brandMsg = this.$refs.brandError;
                    brandMsg.innerHTML = "Vui lòng chọn nhãn hiệu!";
                    brandMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (!category.value) {
                    e.preventDefault();
                    const categoryMsg = this.$refs.categoryError;
                    categoryMsg.innerHTML = "Vui lòng chọn danh mục!";
                    categoryMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                //Xử lý ảnh khi submit
                if (!window.croppedAddImages || !window.croppedAddImages[1]) {
                    e.preventDefault();
                    const img = this.$refs.imageError;
                    img.innerHTML = 'Hình ảnh chính là bắt buộc!';
                    img.scrollIntoView();
                    return;
                }

                // Duyệt qua mảng các ảnh đã crop
                Object.keys(window.croppedAddImages).forEach(index => {
                    const blob = window.croppedAddImages[index];
                    if (!blob) return;
                    const file = new File([blob], `image_${index}.png`, {
                        type: "image/png"
                    });
                    //Hình thumbnail
                    if (index == "1") {
                        thumbnail.items.add(file);
                        console.log('Đã thêm hình ảnh thumbnail');
                    } else {
                        //Hình ảnh con
                        images.items.add(file);
                        console.log("Đã thêm hình con!");
                    }
                });
                this.$refs.thumbnailInput.files = thumbnail.files;
                this.$refs.imagesInput.files = images.files;
            },

            handleImageChange(event) {
                const msg = this.$refs.imageError;
                const index = event.target.getAttribute('data-index'); // Lấy số 1, 2, 3...
                if (event.target.files && event.target.files[0]) {

                    if (!event.target.files[0].type.startsWith('image/')) {
                        msg.innerHTML = 'Chỉ được chọn hình ảnh!';
                        event.target.value = '';
                        return;
                    }
                    var reader = new FileReader();

                    reader.onload = (e) => {
                        // Hiển thị vùng cắt ảnh
                        this.$refs.cropArea.classList.remove('hidden');
                        //Ẩn vùng thêm ảnh
                        this.$refs.imageArea.classList.add('hidden');
                        // Nạp ảnh vào Croppie
                        this.uploadCrop.bind({
                            url: e.target.result
                        });

                        // Lưu lại index hiện tại
                        this.$refs.cropButton.setAttribute('data-target', index);
                        this.$refs.cropCancelButton.setAttribute('data-target', index);

                        msg.innerHTML = '';
                    }

                    reader.readAsDataURL(event.target.files[0]);
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
                    msg.innerHTML = ''
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
                if (this.sizes) {
                    this.tsSize.setValue(this.sizes);
                }
            },

            getHex(id) {
                return this.colorMap[id];
            },

            handleEdit() {
                this.variants.forEach(variant => {
                    let sale_price = this.$refs.inputSalePrice.value;
                    if (sale_price) variant.sale_price = Number(sale_price);
                    let price = this.$refs.inputPrice.value;
                    if (price) variant.price = Number(price);
                    let quantity = this.$refs.inputStock.value;
                    if (quantity) variant.quantity = Number(quantity);
                })
            },

            handleGenerateClick() {
                this.generateVariants();
                console.log("Dữ liệu biến thể:", this.variants)
                console.table(this.variants)
                if (this.variants.length > 0) {
                    this.isExpanded = true;
                }
            },

            generateVariants() {
                const inputColor = this.$refs.colorSelect;
                const inputSize = this.$refs.sizeSelect;
                const msg = this.$refs.generateError;

                if (!inputColor.value || !inputSize.value) {
                    msg.innerHTML = "Vui lòng chọn ít nhất 1 Kích cỡ và 1 Màu sắc";
                    return;
                }

                if (this.variants.length > 0 && !confirm("Dữ liệu bảng hiện tại sẽ bị thay thế. Bạn có chắc chắn?")) {
                    return;
                }
                const priceInput = Number(this.$refs.inputPrice.value) || 0;
                const salePriceInput = Number(this.$refs.inputSalePrice.value) || 0;

                // Logic tạo các biến thể
                let results = []
                this.colors.forEach(c => {
                    this.sizes.forEach(s => {
                        results.push({
                            id: c + "-" + s.id,
                            // Lưu object để hiển thị tên
                            size: s,

                            //Lưu id để xử lý
                            color_id: c,
                            size_id: s.id,

                            // Các trường nhập liệu
                            price: priceInput,
                            sale_price: salePriceInput > priceInput ? priceInput : salePriceInput,
                            quantity: Number(this.$refs.inputStock.value) || 0,
                            status: true,
                        });
                    });
                });
                this.variants = results;
            },
            removeRow(index) {
                if (confirm('Xóa biến thể này?')) {
                    this.variants.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush
