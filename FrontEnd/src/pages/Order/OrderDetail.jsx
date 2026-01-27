import { User, MapPin, Calendar, CreditCard, ShoppingBag } from "lucide-react";

export default function OrderDetail({ order }) {
  // const getApproxItemPrice = (total, qty) => total / qty; 

  return (
    <div className="text-[#0a0d1a] font-sans">
      {/* 1. Customer & Shipping Info Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-dashed border-gray-300">
        <div className="space-y-4">
          <h4 className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#D2B48C]">
            <User size={14} /> Khách hàng
          </h4>
          <div className="pl-6">
             <p className="font-serif text-lg font-medium">{order.customer}</p>
             <div className="flex items-center gap-2 text-sm text-gray-500 mt-1">
                <MapPin size={14} />
                <span>Việt Nam (Default Address)</span>
             </div>
          </div>
        </div>

        {/* Column 2: Order Info */}
        <div className="space-y-4">
          <h4 className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#D2B48C]">
            <Calendar size={14} /> Thông tin đơn
          </h4>
          <div className="pl-6 space-y-2">
            <div className="flex justify-between md:justify-start gap-12">
                <span className="text-sm text-gray-400">Ngày đặt:</span>
                <span className="text-sm font-medium">{order.date}</span>
            </div>
            <div className="flex justify-between md:justify-start gap-12">
                <span className="text-sm text-gray-400">Phương thức:</span>
                <span className="text-sm font-medium flex items-center gap-2">
                    <CreditCard size={14} /> COD
                </span>
            </div>
          </div>
        </div>
      </div>

      <div className="mb-8">
        <h4 className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#D2B48C] mb-6">
            <ShoppingBag size={14} /> Danh sách sản phẩm
        </h4>
        
        <div className="space-y-4">
          {order.items.map((item, index) => (
            <div 
              key={item.id || index} 
              className="flex items-center justify-between py-2"
            >
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 bg-gray-100 flex items-center justify-center rounded-sm text-gray-300">
                   <ShoppingBag size={16} />
                </div>
                
                <div>
                    <p className="font-medium text-sm text-[#0a0d1a]">
                        {item.nameItem || item.name}
                    </p>
                    <p className="text-xs text-gray-400 mt-0.5">Mã SP: #ITEM-{index + 101}</p>
                </div>
              </div>

              <div className="text-right">
                <span className="block text-sm font-medium">x{item.quantity}</span>
                <span className="block text-xs text-gray-500">
                    {(order.total / order.items.length).toLocaleString()}₫
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className="bg-[#f3f3f3] p-6 -mx-6 -mb-6 md:rounded-b-lg">
         <div className="flex flex-col gap-3 text-sm">
            <div className="flex justify-between text-gray-500">
                <span>Tạm tính</span>
                <span>{order.total.toLocaleString()}₫</span>
            </div>
            <div className="flex justify-between text-gray-500">
                <span>Phí vận chuyển</span>
                <span>0₫</span>
            </div>
            <div className="border-t border-gray-300 my-1"></div>
            <div className="flex justify-between items-end">
                <span className="font-bold uppercase tracking-wider text-[#0a0d1a]">Tổng thanh toán</span>
                <span className="font-serif text-xl font-medium text-[#0a0d1a]">
                    {order.total.toLocaleString()}₫
                </span>
            </div>
         </div>
      </div>
    </div>
  );
}