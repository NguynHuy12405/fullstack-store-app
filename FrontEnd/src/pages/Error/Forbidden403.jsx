import { ShieldX, ArrowLeft, Home, Lock } from 'lucide-react';
import { useNavigate } from 'react-router';

export default function Forbidden403() {
  const navigate = useNavigate();

  return (
    <div className="min-h-screen w-full flex flex-col items-center justify-center bg-[#0a0d1a] p-6 relative overflow-hidden">
      <div className="absolute top-0 left-0 w-full h-2 bg-[#D2B48C]"></div>
      <div className="absolute -bottom-20 -right-20 text-[#1a1e2e] opacity-20 select-none">
        <span className="text-[300px] font-serif font-bold">403</span>
      </div>

      <div className="bg-white w-full max-w-lg p-10 md:p-14 relative z-10 shadow-2xl">
        <div className="flex flex-col items-center mb-8">
          <div className="w-16 h-16 bg-[#0a0d1a] text-[#D2B48C] flex items-center justify-center mb-6">
            <Lock size={32} strokeWidth={1.5} />
          </div>
          <h2 className="text-xs font-bold uppercase tracking-[0.3em] text-[#D2B48C]">
            Restricted Access
          </h2>
        </div>

        <div className="text-center mb-8">
          <h1 className="text-6xl font-serif font-medium text-[#0a0d1a] mb-2">
            403
          </h1>
          <p className="text-xl font-light text-gray-400 uppercase tracking-widest">
            Forbidden
          </p>
        </div>

        <div className="text-center mb-10 border-y border-gray-100 py-6">
          <p className="text-gray-600 font-light leading-relaxed">
            Xin lỗi, bạn không có "thẻ thành viên" để bước vào khu vực này.
            <br />
            Vui lòng liên hệ quản lý hoặc quay lại sảnh chính.
          </p>
        </div>

        <div className="space-y-3">
          <button 
            onClick={() => window.history.back()}
            className="w-full flex items-center justify-center gap-3 bg-[#0a0d1a] text-white hover:bg-[#D2B48C] hover:text-[#0a0d1a] py-4 uppercase text-xs font-bold tracking-[0.2em] transition-all duration-300 group"
          >
            <ArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform" />
            Quay lại trang trước
          </button>
          
          <button 
            onClick={() => navigate("/")}
            className="w-full flex items-center justify-center gap-3 bg-transparent border border-[#0a0d1a] text-[#0a0d1a] hover:border-[#D2B48C] hover:text-[#D2B48C] py-4 uppercase text-xs font-bold tracking-[0.2em] transition-all duration-300"
          >
            <Home size={16} />
            Về trang chủ
          </button>
        </div>
      </div>

      <div className="absolute bottom-8 text-center">
        <p className="text-[#D2B48C] text-[10px] uppercase tracking-[0.4em] opacity-60">
          Fashion Brand Security
        </p>
      </div>
    </div>
  );
};