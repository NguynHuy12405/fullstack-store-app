import { X, UploadCloud, Save } from 'lucide-react';
import { useEffect } from 'react';
import { useProductStore } from '../../../stores/useProductStore';
import { useCategoryStore } from '../../../stores/useCategoryStore';
import SelectForm from '../SelectForm';

export default function EditProduct({ isOpen, onClose, product, onSave }) {
  const {
    formData,
    images,
    setFormData,
    setImages,
    removeImage,
    resetForm,
  } = useProductStore();
   const { loadCategories, categories } = useCategoryStore();

  useEffect(() => {
    if (isOpen) {
      loadCategories();

      if (product) {
        setFormData({
          name: product.name,
          price: product.price,
          stock: product.stock,
          category: product.category,
          status: product.status,
          description: product.description,
        });

        setImages(product.images || []);
      }
    }
  }, [isOpen, product]);

  if (!isOpen) return null;

  const handleChange = (name, value) => {
    setFormData({ [name]: value });
  };

  // Chọn ảnh mới
  const handleImageChange = (e) => {
    const files = Array.from(e.target.files);
    const newImages = files.map((f) => URL.createObjectURL(f));
    setImages([...images, ...newImages]);
  };

  // Lưu sản phẩm
  const handleSubmitForm = (e) => {
    e.preventDefault();

    const updatedProduct = {
      ...product,
      ...formData,
      price: Number(formData.price),
      stock: Number(formData.stock),
      images,
      avatar: images[0] || null,
    };

    onSave(updatedProduct);
    resetForm();
    onClose();
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
      <div className="bg-[#ffffff] w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">
        <div className="px-6 py-4 border-b flex justify-between items-center bg-gray-50/50">
          <h2 className="text-xl font-bold text-gray-800">Chỉnh sửa sản phẩm</h2>
          <button onClick={onClose} className="p-2 text-gray-400 hover:text-gray-600 rounded-full cursor-pointer">
            <X size={20} />
          </button>
        </div>

        <form onSubmit={handleSubmitForm} className="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
          
          {/* HÌNH ẢNH */}
          <div className="space-y-4">
            <label className="block text-sm font-medium text-gray-700">Hình ảnh sản phẩm</label>

            <label
              className={`border-2 border-dashed rounded-xl h-64 flex flex-col items-center justify-center cursor-pointer transition-all ${
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
                    className="absolute top-3 right-3 bg-[#ffffff]/80 p-1.5 rounded-full shadow-sm hover:text-red-500 cursor-pointer"
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

          {/* FORM */}
          <div className="space-y-4">
            {/* NAME */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
              <input
                type="text"
                name="name"
                value={formData.name}
                onChange={handleChange}
                className="w-full px-4 py-2 border rounded-lg text-sm"
              />
            </div>

            {/* PRICE + CATEGORY */}
            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Giá bán</label>
                <input
                  type="number"
                  name="price"
                  value={formData.price}
                  onChange={handleChange}
                  className="w-full px-4 py-2 border rounded-lg text-sm"
                />
              </div>

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
                <label className="block text-sm font-medium text-gray-700 mb-1">Số lượng kho</label>
                <input
                  type="number"
                  name="stock"
                  value={formData.stock}
                  onChange={handleChange}
                  className="w-full px-4 py-2 border rounded-lg text-sm"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select
                  name="status"
                  value={formData.status}
                  onChange={handleChange}
                  className="w-full px-4 py-2 border rounded-lg text-sm bg-[#ffffff]"
                >
                  <option value="active">Đang bán</option>
                  <option value="draft">Bản nháp</option>
                  <option value="out_stock">Hết hàng</option>
                </select>
              </div>
            </div>

            {/* DESCRIPTION */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
              <textarea
                name="description"
                value={formData.description}
                onChange={handleChange}
                rows="3"
                className="w-full px-4 py-2 border rounded-lg text-sm resize-none"
              ></textarea>
            </div>
          </div>
        </form>

        <div className="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
          <button onClick={onClose} className="px-4 py-2 text-sm text-[#ffffff] bg-[#0a0d1a] rounded-lg hover:bg-[#D2B48C] hover:text-[#ffffff] cursor-pointer">
            Hủy bỏ
          </button>

          <button
            onClick={handleSubmitForm}
            className="px-4 py-2 text-sm text-[#ffffff] bg-[#0a0d1a] rounded-lg hover:bg-[#D2B48C] hover:text-[#ffffff] flex items-center gap-2 cursor-pointer"
          >
            <Save size={16} /> Lưu sản phẩm
          </button>
        </div>
      </div>
    </div>
  );
}
