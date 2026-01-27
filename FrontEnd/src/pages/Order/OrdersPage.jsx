import { useMemo, useState } from "react";
import { useOrderStore } from "../../stores/useOrderStore";
import { useAuthStore } from "../../stores/useAuthStore";
import { useUIStore } from "../../stores/useUIStore";
import { Package, ChevronDown, ChevronUp, Clock, CheckCircle2, XCircle, AlertCircle, Calendar } from "lucide-react";
import { Link } from "react-router-dom";
import OrderDetail from "./OrderDetail";
import Pagination from "../../components/pagination/Pagination";

const STATUS_OPTIONS = ["Tất cả", "Đang xử lý", "Hoàn thành", "Đã hủy"];
const PAGE_SIZE = 5;

export default function OrdersPage() {
  const user = useAuthStore((s) => s.user);
  const getOrdersByCurrentUser = useOrderStore((s) => s.getOrdersByCurrentUser);
  const { statusFilter, setStatusFilter, currentPage, setCurrentPage } = useUIStore();
  const [openOrderId, setOpenOrderId] = useState(null);
  const allOrders = getOrdersByCurrentUser();

  if (!user) {
    return (
      <div className="min-h-[60vh] flex flex-col items-center justify-center bg-white text-[#0a0d1a]">
        <div className="p-4 bg-gray-50 rounded-full mb-4">
            <Package size={32} className="text-[#D2B48C]" />
        </div>
        <p className="text-xl font-light uppercase tracking-wide mb-4">Vui lòng đăng nhập</p>
        <Link to="/login" className="text-xs font-bold border-b border-[#0a0d1a] pb-1 hover:text-[#D2B48C] hover:border-[#D2B48C] transition-all">
            Đến trang đăng nhập
        </Link>
      </div>
    );
  }

  const filteredOrders = useMemo(() => {
    if (statusFilter === "Tất cả") return allOrders;
    return allOrders.filter((o) => o.status === statusFilter);
  }, [allOrders, statusFilter]);

  const totalPages = Math.ceil(filteredOrders.length / PAGE_SIZE);
  const paginatedOrders = filteredOrders.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE
  );

  const toggleOrder = (id) => {
    setOpenOrderId((prev) => (prev === id ? null : id));
  };

  const getStatusConfig = (status) => {
    switch (status) {
      case "Hoàn thành":
        return { icon: <CheckCircle2 size={16} />, color: "text-green-600", bg: "bg-green-50", label: "Completed" };
      case "Đã hủy":
        return { icon: <XCircle size={16} />, color: "text-red-600", bg: "bg-red-50", label: "Cancelled" };
      default: // Đang xử lý
        return { icon: <Clock size={16} />, color: "text-[#D2B48C]", bg: "bg-[#0a0d1a]", label: "Processing" };
    }
  };

  return (
    <div className="min-h-screen bg-white text-[#0a0d1a] font-sans pb-20">
      <div className="bg-[#0a0d1a] text-white py-12 mb-10">
        <div className="container mx-auto px-4 max-w-5xl">
            <h1 className="text-3xl md:text-4xl font-serif italic mb-2">Lịch Sư Đơn Hàng</h1>
            <p className="text-xs uppercase tracking-widest text-[#D2B48C] opacity-80">Theo dõi hành trình đơn hàng của bạn</p>
        </div>
      </div>

      <div className="container mx-auto px-4 max-w-5xl">
        <div className="flex flex-wrap gap-8 border-b border-gray-100 mb-10">
          {STATUS_OPTIONS.map((status) => (
            <button
              key={status}
              onClick={() => { setStatusFilter(status); setCurrentPage(1); }}
              className={`pb-4 text-xs font-bold uppercase tracking-[0.15em] transition-all relative cursor-pointer ${
                statusFilter === status 
                  ? "text-[#0a0d1a]" 
                  : "text-gray-400 hover:text-[#D2B48C]"
              }`}
            >
              {status}
              {statusFilter === status && (
                <span className="absolute bottom-0 left-0 w-full h-0.5 bg-[#D2B48C]" />
              )}
            </button>
          ))}
        </div>

        <div className="space-y-6">
          {paginatedOrders.length === 0 && (
            <div className="text-center py-20 bg-gray-50 border border-dashed border-gray-300">
               <Package size={40} className="mx-auto text-gray-300 mb-4" />
               <p className="text-gray-500 font-light">Không tìm thấy đơn hàng nào trong mục này.</p>
            </div>
          )}

          {paginatedOrders.map((order) => {
            const statusConfig = getStatusConfig(order.status);
            const isOpen = openOrderId === order.id;

            return (
              <div
                key={order.id}
                className={`group border transition-all duration-300 ${
                    isOpen ? "border-[#0a0d1a] shadow-xl" : "border-gray-200 hover:border-[#D2B48C]"
                }`}
              >
                <div 
                    className="p-6 md:p-8 cursor-pointer bg-white"
                    onClick={() => toggleOrder(order.id)}
                >
                  <div className="flex flex-col md:flex-row justify-between md:items-center gap-6">
                    <div className="flex items-center gap-6">
                        <div className="w-12 h-12 bg-[#f8f8f8] flex items-center justify-center text-[#0a0d1a]">
                            <Package size={20} />
                        </div>
                        <div>
                            <p className="text-xs text-gray-400 uppercase tracking-wider mb-1">Order ID</p>
                            <p className="font-serif text-xl font-medium">#{order.id}</p>
                        </div>
                        <div className="hidden md:block w-px h-10 bg-gray-100 mx-2"></div>
                        <div className="hidden md:block">
                            <p className="text-xs text-gray-400 uppercase tracking-wider mb-1">Date</p>
                            <div className="flex items-center gap-2 text-sm font-medium">
                                <Calendar size={14} className="text-[#D2B48C]" />
                                {order.date}
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center justify-between md:justify-end gap-6 md:gap-10 flex-1">
                         <div className="text-right">
                            <p className="text-xs text-gray-400 uppercase tracking-wider mb-1">Total</p>
                            <p className="font-bold text-[#0a0d1a]">
                                {new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(order.total)}
                            </p>
                         </div>
                         <div className={`flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider border ${
                             order.status === 'Đang xử lý' 
                                ? 'bg-[#0a0d1a] text-white border-[#0a0d1a]' 
                                : 'bg-white border-gray-200 text-[#0a0d1a]'
                         }`}>
                             {statusConfig.icon}
                             <span className="hidden sm:inline">{order.status}</span>
                         </div>

                         <button className={`transition-transform cursor-pointer duration-300 ${isOpen ? 'rotate-180 text-[#D2B48C]' : 'text-gray-400'}`}>
                             <ChevronDown size={24} />
                         </button>
                    </div>
                  </div>
                </div>

                <div 
                    className={`overflow-hidden transition-[max-height] duration-500 ease-in-out bg-[#fafafa] ${
                    isOpen ? "max-h-[1000px] border-t border-gray-100" : "max-h-0"
                    }`}
                >
                  <div className="p-6 md:p-8">
                    <OrderDetail order={order} />
                    <div className="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                         <button className="text-xs font-bold cursor-pointer uppercase tracking-widest text-[#0a0d1a] hover:text-[#D2B48C] transition-colors underline decoration-gray-300 underline-offset-4">
                             In hóa đơn
                         </button>
                    </div>
                  </div>
                </div>
              </div>
            );
          })}
        </div>

        <div className="mt-12 flex justify-end">
            {totalPages > 1 && (
                <Pagination
                  totalPages={totalPages}
                  currentPage={currentPage}
                  onPageChange={paginatedOrders}
                />
            )}
        </div>
      </div>
    </div>
  );
}