import { X, UploadCloud, Save, Download } from 'lucide-react';
import { useProductStore } from '../../../stores/useProductStore';
import { useEffect } from 'react';
import { useCategoryStore } from '../../../stores/useCategoryStore';
import InputForm from "../InputForm";
import SelectForm from "../SelectForm";
import TextareaForm from "../TextareaForm";

export default function AddProduct({ isOpen, onClose }) {
  const { addProduct, setFormData, setImages, formData, images, removeImage, resetForm } = useProductStore();
  const { loadCategories, categories } = useCategoryStore();

  useEffect(() => {
    if (isOpen) loadCategories();
  }, [isOpen]);

  if (!isOpen) return null;

  const handleChange = (name, value) => {
    setFormData({ [name]: value });
  };

  const handleImageChange = (e) => {
    const files = Array.from(e.target.files);
    const newImages = files.map((f) => URL.createObjectURL(f));
    setImages([...images, ...newImages]);
  };

  const handleSubmitForm = (e) => {
    e.preventDefault();

    const newProduct = {
      id: Date.now(),
      ...formData,
      price: Number(formData.price),
      stock: Number(formData.stock),
      images,
      avatar: images[0] || null,
    };

    addProduct(newProduct);
    resetForm();
    onClose();
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
      <div className="bg-[#ffffff] w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">
        <div className="px-6 py-4 border-b flex justify-between items-center bg-gray-50/50">
          <h2 className="text-xl font-bold text-gray-800">Thêm sản phẩm mới</h2>
          <button onClick={onClose} className="p-2 text-gray-400 hover:text-gray-600 rounded-full cursor-pointer">
            <X size={20} />
          </button>
        </div>

        <form onSubmit={handleSubmitForm} className="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
          {/* HÌNH ẢNH */}
          <div className="space-y-4">
            <label className="block text-sm font-medium text-gray-700">Hình ảnh sản phẩm</label>
            <label
              className={`border-2 border-dashed rounded-xl h-64 flex flex-col items-center justify-center cursor-pointer transition-all 
              ${
                images.length
                  ? 'border-blue-500 bg-blue-50/30'
                  : 'border-gray-300 hover:border-blue-400 hover:bg-gray-50'
              }`}
            >
              {images.length > 0 ? (
                <div className="relative w-full h-full p-2">
                  <img src={images[0]} alt="Preview" className="w-full h-full object-contain rounded-lg" />
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      removeImage(0);
                    }}
                    className="absolute top-3 right-3 bg-[#ffffff]/80 p-1.5 rounded-full shadow-sm hover:text-red-500"
                  >
                    <X size={16} />
                  </button>
                </div>
              ) : (
                <>
                  <div className="bg-blue-50 p-3 rounded-full mb-3 text-blue-600 transition-transform">
                    <UploadCloud size={24} />
                  </div>
                  <p className="text-sm font-medium text-gray-700">Nhấn để tải ảnh lên</p>
                  <p className="text-xs text-gray-400 mt-1">PNG, JPG (Tối đa 5MB)</p>
                </>
              )}

              <input type="file" className="hidden" multiple onChange={handleImageChange} accept="image/*" />
            </label>

            {images.length > 1 && (
              <div className="grid grid-cols-4 gap-2 mt-2">
                {images.slice(1).map((img, index) => (
                  <div key={index} className="relative group">
                    <img src={img} className="w-full h-20 object-cover rounded-lg border" />

                    <button
                      type="button"
                      onClick={() => removeImage(index + 1)}
                      className="absolute -top-2 -right-2 bg-[#ffffff] shadow p-1 rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer"
                    >
                      <X size={14} className="text-red-500" />
                    </button>
                  </div>
                ))}
              </div>
            )}
          </div>

          <div className="space-y-4">
            {/* NAME */}
            <div>
              <InputForm
                label={"Tên sản phẩm"}
                type="text"
                name="name"
                value={formData.name}
                onChange={handleChange}
                placeholder="Nhập tên sản phẩm..."
                className="w-full px-4 py-2 border rounded-lg text-sm"
              />
            </div>

            <div className="grid grid-cols-2 gap-4">
              {/* PRICE */}
              <div>
                <InputForm
                  label={"Giá bán"}
                  type="number"
                  name="price"
                  value={formData.price}
                  onChange={handleChange}
                  placeholder="0"
                  className="w-full px-4 py-2 border rounded-lg text-sm"
                />
              </div>

              {/* CATEGORY */}
              <div>
                <SelectForm
                  label="Danh mục"
                  name="category"
                  value={formData.category}
                  onChange={handleChange}
                  options={categories.map(c => ({
                    id: c.id,
                    value: c.slug,
                    label: c.name
                  }))}
                />
              </div>
            </div>

            {/* STOCK + STATUS */}
            <div className="grid grid-cols-2 gap-4">
              <div>
                <InputForm
                  label={"Số lượng kho"}
                  type="number"
                  name="stock"
                  value={formData.stock}
                  onChange={handleChange}
                  placeholder="0"
                  className="w-full px-4 py-2 border rounded-lg text-sm"
                />
              </div>

              <div>
                <SelectForm
                  label="Trạng thái"
                  name="status"
                  value={formData.status}
                  onChange={handleChange}
                  options={[
                    { value: "Còn hàng", label: "Còn hàng" },
                    { value: "Sắp hết", label: "Sắp hết" },
                    { value: "Hết hàng", label: "Hết hàng" }
                  ]}
                />
              </div>
            </div>

            {/* DESCRIPTION */}
            <div>
              <TextareaForm
                label={"Mô tả chi tiết"}
                name="description"
                value={formData.description}
                onChange={handleChange}
                rows="3"
                placeholder="Nhập mô tả..."
                className="w-full px-4 py-2 border rounded-lg text-sm resize-none"
              />
            </div>
          </div>
        </form>

        <div className="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
          <button className="px-4 py-2 text-sm font-medium text-[#ffffff] bg-[#0a0d1a] rounded-lg hover:bg-[#D2B48C] hover:text-[#ffffff] cursor-pointer flex items-center gap-2">
            <Download size={20} /> Thêm file Excel
          </button>

          <div className="flex gap-3">
            <button onClick={onClose} className="px-4 py-2 text-sm font-medium bg-[#ffffff] border rounded-lg hover:bg-gray-50 cursor-pointer">
              Hủy bỏ
            </button>

            <button
              onClick={handleSubmitForm}
              className="px-4 py-2 text-sm font-medium text-[#ffffff] bg-[#0a0d1a] rounded-lg hover:bg-[#D2B48C] hover:text-[#ffffff] cursor-pointer flex items-center gap-2"
            >
              <Save size={16} /> Thêm sản phẩm
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
