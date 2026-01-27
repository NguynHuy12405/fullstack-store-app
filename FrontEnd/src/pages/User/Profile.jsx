import { useAuthStore } from "../../stores/useAuthStore";
import { Mail, Phone, MapPin, Calendar, Edit3, ShoppingBag, Heart, LogOut } from 'lucide-react';
import StatItem from "../../components/stat/StatItem";
import InfoRow from "../../components/InfoRow";

export default function Profile() {
  const user = useAuthStore((state) => state.user);

  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#0a0d1a] text-[#D2B48C]">
        <div className="animate-pulse tracking-widest uppercase text-sm">Loading Profile...</div>
      </div>
    );
  }

  // Giả lập thêm dữ liệu nếu user store chưa có (để demo hiển thị đầy đủ)
  const userDetails = {
    phone: user.phone || "+84 90 123 4567",
    address: user.address || "District 1, Ho Chi Minh City",
    joinDate: "Oct 2023",
    role: "VIP Member",
    stats: {
      orders: 12,
      wishlist: 5,
      reviews: 8
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4 md:p-8 font-sans">
      <div className="w-full max-w-4xl bg-[#ffffff] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[500px]">

        {/* Left Column */}
        <div className="w-full md:w-1/3 bg-[#f8f8f8] p-8 flex flex-col items-center justify-center relative border-b md:border-b-0 md:border-r border-[#e5e5e5]">
          <div className="absolute top-0 left-0 w-full h-1 bg-[#D2B48C]" /> 
          <div className="relative group cursor-pointer">
            <div className="absolute -inset-1 bg-linear-to-tr from-[#D2B48C] to-[#0a0d1a] rounded-full opacity-20 group-hover:opacity-40 transition duration-500 blur"></div>
            <img 
              src={user.avatar || "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&h=200"} 
              alt={user.name} 
              className="relative w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg"
            />
            <div className="absolute bottom-0 right-0 bg-[#0a0d1a] p-2 rounded-full text-white border-2 border-white">
                <Edit3 size={14} />
            </div>
          </div>

          <h1 className="mt-6 text-2xl font-serif font-bold text-[#0a0d1a] tracking-wide text-center">
            {user.name}
          </h1>
          <span className="mt-2 text-xs font-bold tracking-widest text-[#D2B48C] uppercase">
            {userDetails.role}
          </span>
          <p className="mt-1 text-xs text-gray-400">Member since {userDetails.joinDate}</p>

          <div className="mt-8 w-full flex justify-between px-4">
             <StatItem count={userDetails.stats.orders} label="Orders" />
             <StatItem count={userDetails.stats.wishlist} label="Wishlist" />
             <StatItem count={userDetails.stats.reviews} label="Reviews" />
          </div>
        </div>

        {/* Right Column */}
        <div className="w-full md:w-2/3 p-8 md:p-12 flex flex-col justify-between">
          <div>
            <div className="flex justify-between items-center mb-8">
              <h2 className="text-xl font-light text-[#0a0d1a] uppercase tracking-wider border-l-4 border-[#D2B48C] pl-4">
                Thông tin cá nhân
              </h2>
              <button className="text-xs font-semibold text-[#D2B48C] hover:text-[#0a0d1a] transition-colors uppercase tracking-wide cursor-pointer">
                Chỉnh sửa
              </button>
            </div>

            <div className="space-y-6">
              <InfoRow icon={<Mail size={18} />} label="Email" value={user.email} />
              <InfoRow icon={<Phone size={18} />} label="Điện thoại" value={userDetails.phone} />
              <InfoRow icon={<MapPin size={18} />} label="Địa chỉ" value={userDetails.address} />
              <InfoRow icon={<Calendar size={18} />} label="Ngày sinh" value="01/01/1995" />
            </div>
          </div>

          <div className="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
            <button className="text-sm cursor-pointer text-gray-500 hover:text-[#0a0d1a] transition-colors underline decoration-[#D2B48C]">
              Đổi mật khẩu
            </button>
            <button className="flex items-center cursor-pointer gap-2 px-6 py-3 bg-[#0a0d1a] text-white text-xs font-bold uppercase tracking-widest hover:bg-[#D2B48C] hover:text-[#0a0d1a] hover:shadow-lg transition-all duration-300">
              <LogOut size={14} />
              Đăng xuất
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}