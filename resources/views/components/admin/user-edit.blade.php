@props(['roles'])
<div x-data="{ open: {{ $errors->edit->any()? 'true' : 'false' }}, oldData: {{ $errors->edit->any()? json_encode(old()) : json_encode((object)[]) }}, user: {} }"
    x-show="open"
    x-on:open-edit-modal.window="open = true; user = $event.detail"
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
                x-data="roleEditManager()"
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
                            <h3 class="text-lg font-bold mr-2">Thêm tài khoản mới</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" :action="'{{ route('admin.user.update', ['user' => ':id']) }}'.replace(':id', oldData.id)" x-ref="userForm" class="space-y-6" @submit="handleSubmit($event)">
                                @csrf
                                @method('PUT')
                                @foreach ($errors->edit->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class="grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Tên tài khoản</label>
                                        <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                        <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên người dùng" maxlength="255">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Chức vụ</label>
                                        <span x-ref="roleError" class="text-xs text-red-500 italic">*</span>
                                        <select x-ref="roleSelect" name="role_id" class="cursor-pointer">
                                            <option value="" disabled selected hidden>Chọn chức vụ...</option>
                                            @foreach ($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Email</label>
                                        <span x-ref="emailError" class="text-xs text-red-500 italic">*</span>
                                        <input value="{{ old('email_name') }}" x-ref="emailInput" type="text" name="email_name" class="w-full p-2.5 border rounded text-sm" readonly>
                                    </div>
                                </div>
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Số điện thoại</label>
                                        <span x-ref="phoneError" class="text-xs text-red-500 italic">*</span>
                                        <input x-model="oldData.phone" x-ref="phoneInput" type="text" name="phone" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập số điện thoại" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>

                                <!-- Trạng thái -->
                                <div class=" grid grid-cols-1 md:grid-cols-1 gap-4">
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <span class="text-xs font-semibold uppercase text-gray-500">Giới tính</span>
                                        <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="gender" value="1" class="peer hidden" x-model="oldData.gender">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-blue-400 hover:text-blue-600">
                                                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                                    Nam
                                                </span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="gender" value="0" class="peer hidden" x-model="oldData.gender">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-pink-500 peer-checked:shadow-sm text-pink-400 hover:text-pink-600">
                                                    <span class="w-2 h-2 rounded-full bg-pink-400 mr-2"></span>
                                                    Nữ
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                        <span class="text-xs font-semibold uppercase text-gray-500">Khả năng đánh giá</span>
                                        <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="review" value="1" class="peer hidden" x-model="oldData.review">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                                    Hoạt động
                                                </span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="review" value="0" class="peer hidden" x-model="oldData.review">
                                                <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-red-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                    <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>
                                                    Khóa
                                                </span>
                                            </label>
                                        </div>
                                    </div>
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
                                                    Khóa
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
                                <input name="id" type="hidden" x-model="oldData.id">
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
    function roleEditManager() {
        return {
            init() {
                const self = this;
                this.tsRole = new TomSelect(this.$refs.roleSelect, {
                    create: async function(input, callback) {
                        if (confirm('Xác nhận thêm?')) {
                            const data = {
                                name: input
                            };
                            const msg = self.$refs.roleError;
                            try {
                                msg.innerHTML = "...";
                                const res = await axios.post("{{ route('admin.role.store') }}", data)
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
                    sortField: {
                        field: "text",
                        order: "asc"
                    }
                });

                this.$watch('user', (user) => {
                    this.oldData.name = String(user.name);
                    this.oldData.role_id = String(user.role_id);
                    this.oldData.gender = String(user.gender);
                    this.oldData.status = String(user.status);
                    this.oldData.review = String(user.review);
                    this.oldData.phone = String(user.phone);
                    this.oldData.id = String(user.id);
                    this.tsRole.setValue(String(user.role_id));
                    this.$refs.emailInput.value = user.email;
                })
                if (this.oldData) {
                    this.tsRole.setValue(String(this.oldData.role_id));
                }

            },

            handleSubmit(e) {
                //Kiểm tra trước khi submit
                const name = this.$refs.nameInput;
                const phone = this.$refs.phoneInput;
                const email = this.$refs.emailInput;
                const role = this.$refs.roleSelect;

                if (!name.value) {
                    e.preventDefault();
                    const nameMsg = this.$refs.nameError;
                    nameMsg.innerHTML = "Tên sản phẩm không được để trống!";
                    nameMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }

                if (!role.value) {
                    e.preventDefault();
                    const roleMsg = this.$refs.roleError;
                    roleMsg.innerHTML = "Chức vụ không được để trống!";
                    roleMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }
                if (!phone.value) {
                    e.preventDefault();
                    const phoneMsg = this.$refs.phoneError;
                    phoneMsg.innerHTML = "Số điện thoại không được để trống!";
                    phoneMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }
                if (!email.value) {
                    e.preventDefault();
                    const emailMsg = this.$refs.emailError;
                    emailMsg.innerHTML = "Số điện thoại không được để trống!";
                    emailMsg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    })
                }
            }
        }
    }
</script>
@endpush
