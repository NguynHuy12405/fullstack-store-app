import { useEffect, useMemo } from "react";
import { Plus, Edit2, Trash, Search } from "lucide-react";
import { useProductStore } from "../../../stores/useProductStore";
import StatusBadge from "../../../components/stat/StatusBadge";
import AddProduct from "../../../components/form/CRUD/AddProduct";
import EditProduct from "../../../components/form/CRUD/EditProduct";
import { useUIStore } from "../../../stores/useUIStore";
import Pagination from "../../../components/pagination/Pagination";

const STATUS_OPTIONS = ["Tất cả", "Còn hàng", "Sắp hết", "Hết hàng"];
const PAGE_SIZE = 10;

export default function ManageProducts() {
  const { products, loadProducts, updateProduct } = useProductStore();
  const {
    statusFilter,
    setStatusFilter,
    currentPage,
    setCurrentPage,
    isAddOpen,
    isEditOpen,
    selectedProduct,
    toggleAddModal,
    toggleEditModal,
    setSelectedProduct,
  } = useUIStore();


  const filteredProduct = useMemo(() => {
    if (statusFilter === "Tất cả") return products;
    return products.filter(p => p.status === statusFilter);
  }, [products, statusFilter]);

  // Phân trang
  const totalPages = Math.ceil(filteredProduct.length / PAGE_SIZE);
  const paginatedProduct = filteredProduct.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE
  );

  const handlePaginate = (pageNumber) => {
    setCurrentPage(pageNumber);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  useEffect(() => {
    loadProducts();
  }, []);

  useEffect(() => {
    if (totalPages === 0) {
      setCurrentPage(1);
      return;
    }
    if (currentPage > totalPages) {
      setCurrentPage(totalPages);
    }
  }, [totalPages, currentPage, setCurrentPage]);

  useEffect(() => {
    setCurrentPage(1);
  }, [statusFilter, setCurrentPage]);

  // Mở popup chỉnh sửa
  const handleEditClick = (product) => {
    setSelectedProduct(product);
    toggleEditModal(true);
  };

  // Lưu sản phẩm sau khi sửa
  const handleSaveProduct = (updatedProduct) => {
    updateProduct(updatedProduct);
    toggleEditModal(false);
    setSelectedProduct(null);
  };

  return (
    <div className="bg-gray-50 min-h-screen p-8">
      {/* Header */}
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Sản phẩm</h1>
          <p className="text-gray-500 text-sm mt-1">
            Quản lý kho hàng và danh mục sản phẩm
          </p>
        </div>

        <button
          onClick={() => toggleAddModal(true)}
          className="flex items-center gap-2 text-[#ffffff] bg-[#0a0d1a] hover:bg-[#D2B48C] hover:text-[#0a0d1a] px-5 py-2.5 rounded-xl shadow-sm transition-all text-sm font-medium cursor-pointer"
        >
          <Plus size={18} /> Thêm sản phẩm
        </button>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div className="p-4 border-b border-gray-100 flex gap-4">
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
            <input 
              type="text" 
              placeholder="Tìm kiếm theo tên, email..." 
              className="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
            />
          </div>
          <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)} className="px-4 py-2 border rounded-lg text-sm text-gray-600 focus:outline-none cursor-pointer">
            {STATUS_OPTIONS.map(status => (
              <option key={status} value={status}>{status}</option>
            ))}
          </select>
        </div>

        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
              <th className="px-6 py-4">Tên sản phẩm</th>
              <th className="px-6 py-4">Danh mục</th>
              <th className="px-6 py-4">Giá bán</th>
              <th className="px-6 py-4 text-center">Kho</th>
              <th className="px-6 py-4">Trạng thái</th>
              <th className="px-6 py-4 text-right">Thao tác</th>
            </tr>
          </thead>

          <tbody className="divide-y divide-gray-50">
            {paginatedProduct.map((item) => (
              <tr
                key={item.id}
                className="hover:bg-gray-50/80 transition-colors group"
              >
                <td className="px-6 py-4">
                  <div className="flex items-center gap-3">
                    <img
                      src={item.image}
                      alt={item.name}
                      className="w-10 h-10 rounded-lg object-cover border border-gray-100"
                    />
                    <span className="font-medium text-gray-800">
                      {item.name}
                    </span>
                  </div>
                </td>

                <td className="px-6 py-4 text-gray-600 text-sm">
                  {item.category}
                </td>

                <td className="px-6 py-4 font-medium text-gray-800">
                  {item.price.toLocaleString()} đ
                </td>

                <td className="px-6 py-4 text-center text-gray-600">
                  {item.stock}
                </td>

                <td className="px-6 py-4">
                  <StatusBadge status={item.status} />
                </td>

                <td className="px-6 py-4 text-right">
                  <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button
                      onClick={() => handleEditClick(item)}
                      className="p-2 text-gray-500 hover:text-[#D2B48C] hover:bg-[#E7D7BD] rounded-lg transition-colors cursor-pointer"
                    >
                      <Edit2 size={16} />
                    </button>

                    <button className="p-2 text-gray-500 hover:text-[#D2B48C] hover:bg-[#E7D7BD] rounded-lg transition-colors cursor-pointer">
                      <Trash size={16} />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        
        {/* Pagination */}
        <div className="p-4 border-t border-gray-100 flex justify-center items-center text-sm text-gray-500">
          <Pagination totalPages={totalPages} currentPage={currentPage} onPageChange={handlePaginate} />
        </div>
      </div>

      {/* Popup Add */}
      <AddProduct
        isOpen={isAddOpen}
        onClose={() => toggleAddModal(false)}
      />

      {/* Popup Edit */}
      <EditProduct
        isOpen={isEditOpen}
        onClose={() => {
          toggleEditModal(false);
          setSelectedProduct(null);
        }}
        product={selectedProduct}
        onSave={handleSaveProduct}
      />
    </div>
  );
}
