<div x-data="{ open: {{ $errors->edit->any()? 'true' : 'false' }}, oldData: {{ $errors->edit->any()? json_encode(old()) : json_encode((object)[]) }}, contact: {} }"
    x-show="open"
    x-on:open-edit-modal.window="open = true; contact = $event.detail"
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
                x-data="contacteditManager()"
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
                            <h3 class="text-lg font-bold mr-2">Sửa liên hệ</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" :action="'{{ route('admin.contact.update', ['contact' => ':id']) }}'.replace(':id', oldData.id)" x-ref="contactForm" class="space-y-6" @submit="handleSubmit($event)">
                                @method('PUT')
                                @csrf
                                @foreach ($errors->edit->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class=" grid grid-cols-1 md:grid-cols-1">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">Tên liên hệ</label>
                                            <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                            <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên liên hệ" maxlength="255">
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5">
                                    <div class=" grid grid-cols-1 md:grid-cols-1">
                                        <div>
                                            <label class="text-xs font-semibold uppercase text-gray-500">URL</label>
                                            <span x-ref="urlError" class="text-xs text-red-500 italic">*</span>
                                            <input x-model="oldData.url" x-ref="urlInput" type="text" name="url" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên liên hệ" maxlength="255">
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
                                <input type="hidden" x-model="oldData.id" name="id">
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
    function contacteditManager() {
        return {
            init() {
                this.$watch('contact', (contact) => {
                    this.oldData.id = contact.id;
                    this.oldData.name = String(contact.name);
                    this.oldData.url = String(contact.url);
                    this.oldData.status = String(contact.status);
                    this.oldData.id = String(contact.id);
                })
            },
            handleSubmit(e) {
                //Kiểm tra trước khi submit
                const name = this.$refs.nameInput;
                if (!name.value) {
                    e.preventDefault();
                    const nameMsg = this.$refs.nameError;
                    nameMsg.innerHTML = "Tên liên hệ không được để trống!";
                    nameMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                const url = this.$refs.urlInput;
                if (!url.value) {
                    e.preventDefault();
                    const urlMsg = this.$refs.urlError;
                    urlMsg.innerHTML = "URL không được để trống!";
                    urlMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }
            }
        }
    }
</script>
@endpush
