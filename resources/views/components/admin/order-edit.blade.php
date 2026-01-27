<div x-data="{ open: {{ $errors->any()? 'true' : 'false' }}, oldData: {{ $errors->any()? json_encode(old()) : json_encode((object)[]) }}, order: {} }"
    x-show="open"
    x-on:open-modal.window="open = true; order = $event.detail"
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
                x-data="orderManager()"
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
                            <h3 class="text-lg font-bold mr-2">Xử lý đơn hàng</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-red-500 transition text-xl px-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Content -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <form method="POST" :action="'{{ route('admin.order.update', ['order' => ':id']) }}'.replace(':id', oldData.order_id)" x-ref="orderForm" class="space-y-6">
                                @csrf
                                @method('PUT')
                                @foreach ($errors->all() as $error)
                                <li class="text-red-700 text-sm flex items-start">
                                    <i class="fas fa-caret-right mt-1 mr-2 text-red-400"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Tên khách hàng</label>
                                        <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                        <input x-model="oldData.name" x-ref="nameInput" type="text" name="name" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập tên khách hàng" maxlength="255">
                                    </div>
                                </div>
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Số điện thoại</label>
                                        <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                        <input x-model="oldData.phone" x-ref="phoneInput" type="text" name="phone" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập số điện thoại" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class=" grid grid-cols-1 md:grid-cols-1">
                                    <div>
                                        <label class="text-xs font-semibold uppercase text-gray-500">Địa chỉ</label>
                                        <span x-ref="nameError" class="text-xs text-red-500 italic">*</span>
                                        <textarea x-model="oldData.address" x-ref="addressInput" name="address" class="w-full p-2.5 border rounded text-sm" placeholder="Nhập địa chỉ" maxlength="1000"></textarea>
                                    </div>
                                </div>
                                <div class="border rounded-xl grid grid-cols-1 md:grid-cols-1 p-5 gap-3">
                                    <!-- Trạng thái -->
                                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                        <div class=" flex flex-col justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                            <p class="text-xs font-semibold uppercase text-gray-500">Trạng thái giao hàng</p>
                                            <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="ship_status_id" value="1" class="peer hidden" x-model="oldData.ship_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-yellow-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                                                        Chưa nhận
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="ship_status_id" value="2" class="peer hidden" x-model="oldData.ship_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                                                        Đang giao
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="ship_status_id" value="3" class="peer hidden" x-model="oldData.ship_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                                        Đã giao
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="ship_status_id" value="4" class="peer hidden" x-model="oldData.ship_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-red-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>
                                                        Giao thất bại
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Trạng thái đơn hàng-->
                                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                        <div class="flex flex-col justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                                            <p class="text-xs font-semibold uppercase text-gray-500">Trạng thái đơn hàng</p>
                                            <div class="inline-flex p-1 bg-gray-100 rounded-lg">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="order_status_id" value="1" class="peer hidden" x-model="oldData.order_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-yellow-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                                                        Chờ xác nhận
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="order_status_id" value="2" class="peer hidden" x-model="oldData.order_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-green-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                                                        Đã xác nhận
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="order_status_id" value="3" class="peer hidden" x-model="oldData.order_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-purple-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span>
                                                        Hoàn thành
                                                    </span>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="order_status_id" value="4" class="peer hidden" x-model="oldData.order_status_id">
                                                    <span class="flex items-center px-4 py-2 text-xs font-bold rounded-md transition-all peer-checked:bg-white peer-checked:text-red-500 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                                        <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>
                                                        Đã hủy
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="order_id" x-model="oldData.order_id">

                                    <!-- Footer -->
                                    <div class="flex justify-end gap-3 pt-3 border-t">
                                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Hủy</button>
                                        <button type="submit" class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800">Lưu</button>
                                    </div>
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
    function orderManager() {
        return {
            init() {
                this.$watch('order', (order) => {
                    this.oldData = {
                        ship_status_id: order.ship_status_id,
                        order_status_id: order.order_status_id,
                        order_id: order.id,
                        name: order.name,
                        phone: order.phone,
                        address: order.address,
                    };
                });
            }
        }
    }
</script>
@endpush
@endonce
