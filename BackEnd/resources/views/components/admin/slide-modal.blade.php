<div id="slideModal" class="fixed inset-0 z-50 invisible opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="modalPanel" class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 relative z-10 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Thêm Mới</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full hover:bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Body --}}
            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <form id="slideForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        {{-- Ảnh --}}
                        <div class="md:col-span-5 space-y-3">
                            <label class="block text-sm font-bold text-gray-700">Hình ảnh</label>
                            
                            <div class="relative w-full aspect-[3/2] bg-gray-100 rounded border-2 border-dashed border-gray-300 overflow-hidden cursor-pointer hover:border-blue-400"
                                    onclick="document.getElementById('fileInput').click()">
                                
                                <img id="previewImg" class="w-full h-full object-cover hidden">
                                
                                <div id="uploadPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    <span class="text-xs mt-1">Chọn ảnh</span>
                                </div>
                            </div>
                            
                            <p id="fileNameDisplay" class="text-xs text-gray-500 font-mono truncate"></p>

                            <input type="file" id="fileInput" name="image" class="hidden" accept="image/*" onchange="previewFile(this)">
                        </div>

                        {{-- Form --}}
                        <div class="md:col-span-7 space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Tiêu đề</label>
                                <input type="text" id="inputTitle" name="title" class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:border-blue-500 focus:ring-0">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Mô tả</label>
                                <textarea id="inputDesc" name="description" rows="2" class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:border-blue-500 focus:ring-0 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Link</label>
                                    <input type="text" id="inputUrl" name="url" class="w-full border-gray-200 rounded text-sm py-2 px-3">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Thứ tự</label>
                                    <input type="number" id="inputOrder" name="sort_order" class="w-full border-gray-200 rounded text-sm py-2 px-3 text-center">
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 pt-2">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="inputStatus" name="status" value="1" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <label for="inputStatus" class="text-sm font-medium text-gray-700">Hiển thị Slide này</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Button --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-600 text-sm font-bold hover:bg-gray-200 rounded">Hủy</button>
                <button onclick="document.getElementById('slideForm').submit()" class="px-6 py-2 bg-black text-white text-sm font-bold rounded hover:bg-gray-800 shadow">Lưu</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ROUTES = {
        store: "{{ route('admin.slideshow.store') }}",
        update: "{{ route('admin.slideshow.update', ':id') }}",
        storage: "{{ asset('storage') }}" + "/"
    };

    const els = {
        modal: document.getElementById('slideModal'),
        panel: document.getElementById('modalPanel'),
        title: document.getElementById('modalTitle'),
        form: document.getElementById('slideForm'),
        method: document.getElementById('methodField'),
        
        // Inputs
        inputTitle: document.getElementById('inputTitle'),
        inputDesc: document.getElementById('inputDesc'),
        inputUrl: document.getElementById('inputUrl'),
        inputOrder: document.getElementById('inputOrder'),
        inputStatus: document.getElementById('inputStatus'),
        inputFile: document.getElementById('fileInput'),
        
        // Image UI
        imgPreview: document.getElementById('previewImg'),
        uploadPlaceholder: document.getElementById('uploadPlaceholder'),
        fileNameDisplay: document.getElementById('fileNameDisplay')
    };

    // --- HÀM 1: Mở Modal ở chế độ Tạo Mới ---
    function openCreateModal() {
        resetForm();
        
        els.title.innerText = "Thêm Slide Mới";
        els.form.action = ROUTES.store;
        els.method.value = "POST";
        els.inputStatus.checked = true; 
        
        showModalUI();
    }

    // --- HÀM 2: Mở Modal ở chế độ Edit ---
    function openEditModal(slide) {
        resetForm();
        
        els.title.innerText = "Cập nhật Slide";
        // Thay thế :id bằng id thật
        els.form.action = ROUTES.update.replace(':id', slide.id);
        els.method.value = "PUT";

        // Đổ dữ liệu vào form
        els.inputTitle.value = slide.title || '';
        els.inputDesc.value = slide.description || '';
        els.inputUrl.value = slide.url || '';
        els.inputOrder.value = slide.sort_order || 0;
        els.inputStatus.checked = (slide.status == 1);

        // Xử lý ảnh cũ
        if (slide.image) {
            els.imgPreview.src = ROUTES.storage + slide.image;
            els.imgPreview.classList.remove('hidden');
            els.uploadPlaceholder.classList.add('hidden');
            els.fileNameDisplay.innerText = slide.image; // Hiện tên string
        }

        showModalUI();
    }

    // --- HÀM 3: Hiển thị giao diện Modal (CSS Classes) ---
    function showModalUI() {
        // Xóa class ẩn
        els.modal.classList.remove('invisible', 'opacity-0');
        
        // Animation cho Panel (Zoom in)
        setTimeout(() => {
            els.panel.classList.remove('scale-95', 'opacity-0');
            els.panel.classList.add('scale-100', 'opacity-100');
        }, 50); // Delay nhỏ để CSS transition bắt được
    }

    // --- HÀM 4: Đóng Modal ---
    function closeModal() {
        // Animation zoom out
        els.panel.classList.remove('scale-100', 'opacity-100');
        els.panel.classList.add('scale-95', 'opacity-0');

        // Ẩn wrapper sau khi animation xong (300ms khớp với duration-300)
        setTimeout(() => {
            els.modal.classList.add('invisible', 'opacity-0');
            resetForm();
        }, 300);
    }

    // --- HÀM 5: Reset Form về trắng ---
    function resetForm() {
        els.form.reset();
        els.imgPreview.src = "";
        els.imgPreview.classList.add('hidden');
        els.uploadPlaceholder.classList.remove('hidden');
        els.fileNameDisplay.innerText = "";
    }

    // --- HÀM 6: Preview ảnh khi chọn file mới ---
    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                els.imgPreview.src = e.target.result;
                els.imgPreview.classList.remove('hidden');
                els.uploadPlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
            els.fileNameDisplay.innerText = "File mới: " + file.name;
        }
    }
</script>