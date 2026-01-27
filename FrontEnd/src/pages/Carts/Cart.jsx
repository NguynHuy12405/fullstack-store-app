import { Link, useNavigate } from 'react-router';
import { useCartStore } from '../../stores/useCartStore';
import { Trash2, Minus, Plus, ArrowRight, ShoppingBag, ArrowLeft } from 'lucide-react';

export default function Cart() {
  const cartItems = useCartStore((state) => state.cartItems);
  const removeFromCart = useCartStore((state) => state.removeFromCart);
  const updateQuantity = useCartStore((state) => state.updateQuantity);
  const clearCart = useCartStore((state) => state.clearCart);
  const getTotalPrice = useCartStore((state) => state.getTotalPrice);
  const navigate = useNavigate();

  if (cartItems.length === 0) {
    return (
      <section className="min-h-[70vh] flex flex-col items-center justify-center bg-white text-[#0a0d1a]">
        <div className="flex flex-col items-center space-y-6 animate-fade-in-up">
          <div className="p-6 bg-[#f8f8f8] rounded-full">
            <ShoppingBag size={48} className="text-[#D2B48C] opacity-80" />
          </div>
          <h2 className="text-3xl font-light uppercase tracking-widest">Giỏ hàng trống</h2>
          <p className="text-gray-400 font-light">Bạn chưa chọn món đồ thời trang nào.</p>
          <Link 
            to="/" 
            className="group flex items-center gap-2 border-b border-[#0a0d1a] pb-1 hover:text-[#D2B48C] hover:border-[#D2B48C] transition-all duration-300 uppercase text-sm tracking-widest font-semibold"
          >
            <ArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform"/>
            Quay lại cửa hàng
          </Link>
        </div>
      </section>
    );
  }

  return (
    <section className="min-h-screen bg-white py-16 font-sans text-[#0a0d1a]">
      <div className="container mx-auto px-4 max-w-6xl">
        {/* Header */}
        <div className="flex justify-between items-end mb-12 border-b border-[#0a0d1a] pb-4">
          <h2 className="text-4xl font-serif font-medium">Shopping Bag</h2>
          <span className="text-[#D2B48C] text-sm uppercase tracking-widest font-bold">
            {cartItems.length} Items
          </span>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
          {/* Left Column */}
          <div className="lg:col-span-2">
            <div className="space-y-8">
              {cartItems.map((item) => (
                <div key={item.id} className="group flex flex-col sm:flex-row gap-6 pb-8 border-b border-gray-100 last:border-0 relative">
                  {/* Product Image */}
                  <div className="w-full sm:w-32 h-40 overflow-hidden bg-gray-50">
                    <img 
                      src={item.image} 
                      alt={item.name} 
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                    />
                  </div>

                  {/* Product Details */}
                  <div className="flex-1 flex flex-col justify-between">
                    <div>
                      <div className="flex justify-between items-start">
                        <h3 className="text-lg font-medium uppercase tracking-wide">{item.name}</h3>
                        <button
                          onClick={() => removeFromCart(item.id)}
                          className="text-gray-300 hover:text-red-500 transition-colors p-1 cursor-pointer"
                        >
                          <Trash2 size={18} />
                        </button>
                      </div>
                      <p className="text-[#D2B48C] font-serif italic mt-1">
                        {Number(item.price).toLocaleString('vi-VN')}₫
                      </p>
                    </div>

                    {/* Quantity & Total Row */}
                    <div className="flex justify-between items-end mt-4 sm:mt-0">
                      <div className="flex items-center gap-4">
                         <span className="text-xs text-gray-400 uppercase tracking-wider">Qty</span>
                         <div className="flex items-center border border-gray-200 px-2 py-1">
                            <button 
                              onClick={() => updateQuantity(item.id, item.quantity - 1)}
                              className="text-gray-400 hover:text-[#0a0d1a] transition-colors cursor-pointer"
                            >
                              <Minus size={14} />
                            </button>
                            <span className="w-8 text-center text-sm font-medium">{item.quantity}</span>
                            <button 
                              onClick={() => updateQuantity(item.id, item.quantity + 1)}
                              className="text-gray-400 hover:text-[#0a0d1a] transition-colors cursor-pointer"
                            >
                              <Plus size={14} />
                            </button>
                         </div>
                      </div>
                      <span className="text-lg font-semibold">
                        {(Number(item.price) * item.quantity).toLocaleString('vi-VN')} ₫
                      </span>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-8">
              <button
                onClick={clearCart}
                className="text-xs cursor-pointer text-gray-400 hover:text-[#0a0d1a] underline decoration-[#D2B48C] transition-colors uppercase tracking-widest"
              >
                Xóa tất cả sản phẩm
              </button>
            </div>
          </div>

          {/* Right Column: Order Summary (Dark Mode Box) */}
          <div className="lg:col-span-1">
            <div className="bg-[#0a0d1a] text-white p-8 sticky top-8">
              <h3 className="text-xl font-serif italic mb-8 border-b border-gray-800 pb-4 text-[#D2B48C]">
                Order Summary
              </h3>
              
              <div className="space-y-4 mb-8 text-sm font-light tracking-wide text-gray-300">
                <div className="flex justify-between">
                  <span>Tạm tính</span>
                  <span className="text-white">{getTotalPrice().toLocaleString('vi-VN')}₫</span>
                </div>
                <div className="flex justify-between">
                  <span>Vận chuyển</span>
                  <span className="text-[#D2B48C]">Miễn phí</span>
                </div>
                <div className="flex justify-between">
                  <span>Thuế (VAT)</span>
                  <span>Đã bao gồm</span>
                </div>
              </div>

              <div className="border-t border-gray-800 pt-6 mb-8">
                <div className="flex justify-between items-end">
                  <span className="text-sm uppercase tracking-widest text-[#D2B48C]">Tổng cộng</span>
                  <span className="text-2xl font-serif font-medium">{getTotalPrice().toLocaleString('vi-VN')}₫</span>
                </div>
              </div>

              <button 
                onClick={() => navigate("/checkout")} 
                className="w-full bg-[#D2B48C] text-[#0a0d1a] cursor-pointer py-4 px-6 flex items-center justify-between group hover:bg-white transition-all duration-300"
              >
                <span className="uppercase text-xs font-bold tracking-[0.2em]">Thanh toán</span>
                <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </button>
              
              <p className="mt-6 text-[10px] text-gray-500 text-center leading-relaxed">
                Thanh toán bảo mật. Đổi trả miễn phí trong vòng 30 ngày đối với sản phẩm còn nguyên tem mác.
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}