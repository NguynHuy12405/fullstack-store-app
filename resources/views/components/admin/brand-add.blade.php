<div x-data="{ open: {{ $errors->add->any()? 'true' : 'false' }}, oldData: {{ $errors->add->any()? json_encode(old()) : json_encode((object)['status' => 1]) }} }"
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
                x-data="brandAddManager()"
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
                            <h3 class="text-lg font-bold mr-2">Thêm thương hiệu mới</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" action="{{ route('admin.brand.store') }}" x-ref="brandForm" class="space-y-6" @submit="handleSubmit($event)" enctype="multipart/form-data">
                                @csrf
                                @foreach ($errors->add->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class=" grid grid-cols-1 md:grid-cols-1">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Tên thương hiệu</label>
                                            <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                            <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên thương hiệu" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                <!-- Hình Ảnh-->
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
                                        <div x-ref="imageArea" class="max-w-4xl mx-auto p-4 flex justify-center">
                                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 h-[300px] w-[300px]">
                                                <div class="md:col-span-2 relative group overflow-hidden rounded-2xl shadow-lg">
                                                    <div x-ref="image-1" class="flex items-center justify-center w-full h-full">
                                                        <label class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                                <p class="mb-2 text-sm text-gray-500 font-semibold text-center px-2">Nhấn để tải ảnh</p>
                                                                <p class="text-xs text-gray-400 font-medium">PNG, JPG hoặc JPEG (Tỉ lệ 1:1)</p>
                                                                <p class="text-xs text-gray-400 font-medium">Tối đa: 2MB</p>
                                                            </div>
                                                            <input x-ref="upload-1" @change="handleImageChange($event)" type="file" class="hidden" accept="image/*" />
                                                        </label>
                                                    </div>
                                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 hidden" x-ref="preview-1" src="">
                                                    <button type="button" @click="$refs['upload-1'].click()" x-ref="btn-replace-1"
                                                        class="hidden absolute top-2 right-2 z-20 bg-blue-500 text-white w-7 h-7 flex items-center justify-center rounded-full hover:bg-blue-600 transition shadow-lg">
                                                        <i class="fas fa-sync text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
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
                                <input type="file" x-ref="imageInput" name="image" class="hidden">
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
    function brandAddManager() {
        return {
            init() {
                window.croppedAddImages = {};
                var el = this.$refs.uploadCrop;
                if (el) {
                    this.uploadCrop = new Croppie(el, {
                        viewport: {
                            width: 200,
                            height: 200,
                            type: 'square'
                        }, // Khung cắt
                        boundary: {
                            width: 300,
                            height: 200,
                        }, // Vùng bao ngoài
                        showZoomer: true, // Hiện thanh trượt zoom
                    });
                }
                // Khi nhấn nút hủy cắt ảnh
                this.$refs.cropCancelButton.addEventListener('click', () => {
                    if (confirm('Xác nhận hủy?')) {
                        //xóa input
                        this.$refs['upload-1'].value = "";
                        //Ẩn vùng cắt ảnh
                        this.$refs.cropArea.classList.add('hidden');
                        //Hiển thị vùng thêm ảnh
                        this.$refs.imageArea.classList.remove('hidden');
                    }
                });
                // 3. Khi nhấn nút "Cắt ảnh"
                this.$refs.cropButton.addEventListener('click', () => {
                    this.uploadCrop.result({
                        type: 'blob',
                        size: {
                            width: 700,
                            height: 700,
                        } // Tỉ lệ 1:1
                    }).then((blob) => {
                        // 2. Hiển thị xem trước vào đúng ô
                        const previewElement = this.$refs['preview-1'];

                        if (previewElement) {
                            var url = URL.createObjectURL(blob);
                            previewElement.src = url;
                            previewElement.classList.remove('hidden');
                            //Ẩn vùng thêm ảnh
                            this.$refs['image-1'].classList.add('hidden');
                            //Hiển thị nút xóa
                            this.$refs['btn-replace-1'].classList.remove('hidden');
                            //Hiển thị vùng thêm ảnh
                            this.$refs.imageArea.classList.remove('hidden');
                            this.$refs.imageError.innerHTML = '';
                        }
                        // 3. Lưu blob vào mảng toàn cục
                        window.croppedAddImages[1] = blob;
                        //Ẩn vùng cắt ảnh
                        this.$refs.cropArea.classList.add('hidden');
                    });
                });
            },

            handleImageChange(event) {
                const msg = this.$refs.imageError;
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
                        msg.innerHTML = '';
                    }

                    reader.readAsDataURL(event.target.files[0]);
                }
            },

            handleSubmit(e) {
                //Kiểm tra trước khi submit
                const name = this.$refs.nameInput;
                if (!name.value) {
                    e.preventDefault();
                    const nameMsg = this.$refs.nameError;
                    nameMsg.innerHTML = "Tên thương hiệu không được để trống!";
                    nameMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (!window.croppedAddImages || !window.croppedAddImages[1]) {
                    e.preventDefault();
                    const img = this.$refs.imageError;
                    img.innerHTML = 'Hình ảnh là bắt buộc!';
                    img.scrollIntoView();
                    return;
                }

                const image = new DataTransfer();
                const blob = window.croppedAddImages[1];
                if (!blob) return;
                const file = new File([blob], `1.png`, {
                    type: "image/png"
                });
                image.items.add(file);
                this.$refs.imageInput.files = image.files;
            }
        }
    }
</script>
@endpush
