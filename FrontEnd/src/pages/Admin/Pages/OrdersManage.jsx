import { Download, Eye, Package, CheckCircle, Clock } from 'lucide-react';
import React, { useState, useMemo } from 'react';
import { useOrderStore } from '../../../stores/useOrderStore';
import { useAuthStore } from '../../../stores/useAuthStore';
import { useUIStore } from '../../../stores/useUIStore';
import OrderDetail from '../../Order/OrderDetail';
import StatCard from "../../../components/stat/StatCard";
import StatusBadge from "../../../components/stat/StatusBadge";
import Pagination from '../../../components/pagination/Pagination';

const STATUS_OPTIONS = ["Tất cả", "Đang xử lý", "Hoàn thành", "Đã hủy"];
const PAGE_SIZE = 10;

export default function ManageOrders()  {
  const user = useAuthStore((s) => s.user);
  const getAllOrders = useOrderStore((s) => s.getAllOrders);
  const getOrdersByCurrentUser = useOrderStore((s) => s.getOrdersByCurrentUser);
  const { statusFilter, setStatusFilter, currentPage, setCurrentPage } = useUIStore();
  const [openOrderId, setOpenOrderId] = useState(null);

  // Phân quyền
  const orders = user?.role === "Admin" ? getAllOrders() : getOrdersByCurrentUser();

  // Lọc theo trạng thái
  const filteredOrders = useMemo(() => {
    if (statusFilter === "Tất cả") return orders;
    return orders.filter(o => o.status === statusFilter);
  }, [orders, statusFilter]);

  // Phân trang
  const totalPages = Math.ceil(filteredOrders.length / PAGE_SIZE);
  const paginatedOrders = filteredOrders.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE
  );

  const toggleOrder = (id) => {
    setOpenOrderId(openOrderId === id ? null : id);
  };

  const handlePaginate = (pageNumber) => {
    setCurrentPage(pageNumber);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <div className="bg-gray-50 min-h-screen p-8">
      {/* Header */}
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Đơn hàng</h1>
          <p className="text-gray-500 text-sm mt-1">Theo dõi các đơn hàng mới nhất</p>
        </div>
        <button className="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 shadow-sm text-sm font-medium transition-all">
          <Download size={16} /> Xuất báo cáo
        </button>
      </div>

      {/* Thống kê */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <StatCard label="Tổng đơn hàng" value={orders.length} icon={Package} color="bg-blue-500" />
        <StatCard label="Đang chờ xử lý" value={orders.filter(o => o.status === "Đang xử lý").length} icon={Clock} color="bg-yellow-500" />
        <StatCard label="Hoàn thành" value={orders.filter(o => o.status === "Hoàn thành").length} icon={CheckCircle} color="bg-green-500" />
      </div>

      {/* Filter */}
      <div className="mb-4 flex items-center gap-4">
        <span className="text-gray-600 text-sm">Lọc trạng thái:</span>
        <select
          value={statusFilter}
          onChange={(e) => { setStatusFilter(e.target.value); setCurrentPage(1); }}
          className="px-2 py-1 border border-gray-300 rounded text-sm"
        >
          {STATUS_OPTIONS.map(status => (
            <option key={status} value={status}>{status}</option>
          ))}
        </select>
      </div>

      {/* Bảng đơn hàng */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
              <th className="px-6 py-4">Mã đơn</th>
              <th className="px-6 py-4">Khách hàng</th>
              <th className="px-6 py-4">Ngày đặt</th>
              <th className="px-6 py-4">Số lượng</th>
              <th className="px-6 py-4">Tổng tiền</th>
              <th className="px-6 py-4">Trạng thái</th>
              <th className="px-6 py-4 text-right">Chi tiết</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-50">
            {paginatedOrders.map(order => (
              <React.Fragment key={order.id}>
                <tr className="hover:bg-gray-50/80 transition-colors">
                  <td className="px-6 py-4 font-semibold text-blue-600 hover:underline cursor-pointer">{order.id}</td>
                  <td className="px-6 py-4">
                    <div className="flex flex-col">
                      <span className={`font-semibold text-sm ${ order.status === "Đã hủy" ? "text-red-700" : order.status === "Hoàn thành" ? "text-blue-700" : "text-gray-700"}`}>{order.customer}</span>
                      <span className="text-xs text-gray-500">
                        {order.userId ? "Tài khoản" : "Khách vãng lai"} {user?.role === "admin" && order.userId && `(ID: ${order.userId})`}
                      </span>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-gray-600 text-sm">{order.date}</td>
                  <td className="px-6 py-4 text-gray-600 text-sm">{order.items.reduce((sum, item) => sum + item.quantity, 0)} sản phẩm</td>
                  <td className="px-6 py-4 font-medium text-gray-800">{order.total.toLocaleString()}₫</td>
                  <td className="px-6 py-4"><StatusBadge status={order.status} /></td>
                  <td className="px-6 py-4 text-right">
                    <button onClick={() => toggleOrder(order.id)} className="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                      <Eye size={18} />
                    </button>
                  </td>
                </tr>
                {openOrderId === order.id && (
                  <tr>
                    <td colSpan="7" className="bg-gray-50">
                      <OrderDetail order={order} />
                    </td>
                  </tr>
                )}
              </React.Fragment>
            ))}
          </tbody>
        </table>

        {/* Pagination */}
        {totalPages > 1 && (
          <Pagination totalPages={totalPages} currentPage={currentPage} onPageChange={handlePaginate} />
        )}
      </div>
    </div>
  );
}
