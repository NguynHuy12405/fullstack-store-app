import { useState } from "react";
import { CreditCard, Truck, MapPin, ShoppingBag, CheckCircle2, Circle } from "lucide-react";
import { useCartStore } from "../../stores/useCartStore";
import { Link } from "react-router";
import InputForm from "../../components/form/InputForm"

export default function Checkout() {
  const cartItems = useCartStore((state) => state.cartItems);
  const [payment, setPayment] = useState("cod");
  const total = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);

  return (
    <div className="min-h-screen bg-white text-[#0a0d1a] font-sans pb-20">
      {/* Header & Breadcrumb */}
      <div className="py-8 border-b border-gray-100 mb-12">
        <div className="container mx-auto px-4 max-w-6xl flex flex-col items-center">
          <h1 className="text-3xl font-serif italic mb-2">Checkout</h1>
          <div className="flex items-center gap-4 text-xs tracking-widest uppercase text-gray-400">
            <Link to="/cart" className="hover:text-[#0a0d1a] transition-colors">Cart</Link>
            <span className="text-[#D2B48C]">/</span>
            <span className="text-[#0a0d1a] font-bold">Information</span>
            <span className="text-[#D2B48C]">/</span>
            <span>Payment</span>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 max-w-6xl grid grid-cols-1 lg:grid-cols-3 gap-12 xl:gap-20">
        <div className="lg:col-span-2 space-y-12">
          <section>
            <h2 className="flex items-center gap-3 text-sm font-bold uppercase tracking-widest mb-6 text-[#0a0d1a]">
              <MapPin size={18} className="text-[#D2B48C]" />
              Địa chỉ giao hàng
            </h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
              <InputForm placeholder="Họ và tên" />
              <InputForm placeholder="Số điện thoại" />
              <InputForm placeholder="Email (để nhận hóa đơn)" type="email" className="md:col-span-2" />
              <InputForm placeholder="Địa chỉ chi tiết (Số nhà, đường...)" className="md:col-span-2" />
              <div className="grid grid-cols-2 gap-4 md:col-span-2">
                 <InputForm placeholder="Tỉnh / Thành phố" />
                 <InputForm placeholder="Quận / Huyện" />
              </div>
            </div>
          </section>

          {/* Section: Payment Method */}
          <section>
            <h2 className="flex items-center gap-3 text-sm font-bold uppercase tracking-widest mb-6 text-[#0a0d1a]">
              <CreditCard size={18} className="text-[#D2B48C]" />
              Phương thức thanh toán
            </h2>

            <div className="space-y-4">
              {/* Option 1: COD */}
              <label 
                className={`relative flex items-center gap-4 p-6 border transition-all cursor-pointer group ${
                  payment === "cod" ? "border-[#0a0d1a] bg-gray-50" : "border-gray-200 hover:border-gray-300"
                }`}
              >
                <input
                  type="radio"
                  name="payment"
                  className="hidden"
                  checked={payment === "cod"}
                  onChange={() => setPayment("cod")}
                />
                <div className={`transition-colors ${payment === 'cod' ? 'text-[#0a0d1a]' : 'text-gray-300'}`}>
                    {payment === 'cod' ? <CheckCircle2 size={20} /> : <Circle size={20} />}
                </div>
                <div>
                  <span className="block font-medium uppercase text-xs tracking-wider">Thanh toán khi nhận hàng (COD)</span>
                  <span className="text-gray-500 text-xs mt-1">Bạn chỉ phải thanh toán khi đã nhận được hàng.</span>
                </div>
              </label>

              {/* Option 2: Bank Transfer */}
              <label 
                className={`relative flex items-center gap-4 p-6 border transition-all cursor-pointer group ${
                  payment === "bank" ? "border-[#0a0d1a] bg-gray-50" : "border-gray-200 hover:border-gray-300"
                }`}
              >
                 <input
                  type="radio"
                  name="payment"
                  className="hidden"
                  checked={payment === "bank"}
                  onChange={() => setPayment("bank")}
                />
                <div className={`transition-colors ${payment === 'bank' ? 'text-[#0a0d1a]' : 'text-gray-300'}`}>
                    {payment === 'bank' ? <CheckCircle2 size={20} /> : <Circle size={20} />}
                </div>
                <div>
                  <span className="block font-medium uppercase text-xs tracking-wider">Chuyển khoản ngân hàng</span>
                  <span className="text-gray-500 text-xs mt-1">Giảm thêm 5% cho đơn hàng thanh toán trước.</span>
                </div>
              </label>
            </div>
          </section>
        </div>

        {/* RIGHT COLUMN: Order Summary */}
        <div className="lg:col-span-1">
            <div className="bg-[#0a0d1a] text-white p-8 sticky top-8 shadow-2xl">
                <div className="flex items-center gap-2 mb-8 border-b border-gray-800 pb-4">
                    <ShoppingBag size={18} className="text-[#D2B48C]" />
                    <h3 className="text-lg font-serif italic text-[#D2B48C]">Your Order</h3>
                </div>
                <div className="max-h-60 overflow-y-auto pr-2 space-y-4 mb-8 custom-scrollbar">
                  {cartItems.map((item) => (
                      <div key={item.id} className="flex gap-4 items-start">
                      <div className="w-12 h-16 bg-gray-800 overflow-hidden shrink-0">
                          <img src={item.image} alt={item.name} className="w-full h-full object-cover opacity-80" />
                      </div>
                      <div className="flex-1 min-w-0">
                          <h4 className="text-sm font-medium text-gray-200 truncate">{item.name}</h4>
                          <p className="text-xs text-gray-500 mt-1">Qty: {item.quantity}</p>
                      </div>
                      <p className="text-sm font-medium text-[#D2B48C]">
                          {(item.price * item.quantity).toLocaleString()}₫
                      </p>
                      </div>
                  ))}
                </div>

                {/* Calculation */}
                <div className="space-y-3 pt-4 border-t border-gray-800 text-sm font-light text-gray-400">
                    <div className="flex justify-between">
                        <span>Tạm tính</span>
                        <span className="text-white">{total.toLocaleString()}₫</span>
                    </div>
                    <div className="flex justify-between">
                        <span>Vận chuyển</span>
                        <span className="text-[#D2B48C]">Miễn phí</span>
                    </div>
                </div>

                {/* Total & Button */}
                <div className="mt-8 pt-6 border-t border-gray-800">
                    <div className="flex justify-between items-end mb-6">
                        <span className="text-xs uppercase tracking-widest text-gray-400">Tổng thanh toán</span>
                        <span className="text-2xl font-serif">{total.toLocaleString()}₫</span>
                    </div>
                    
                    <button className="w-full bg-[#D2B48C] text-[#0a0d1a] py-4 cursor-pointer uppercase text-xs font-bold tracking-[0.2em] hover:bg-white transition-all duration-300 shadow-[4px_4px_0px_0px_rgba(255,255,255,0.2)] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5">
                        Xác nhận đặt hàng
                    </button>
                    
                    <p className="mt-4 text-[10px] text-gray-500 text-center">
                        Bằng việc đặt hàng, bạn đồng ý với Điều khoản dịch vụ & Chính sách bảo mật của chúng tôi.
                    </p>
                </div>
            </div>
        </div>

      </div>
    </div>
  );
}