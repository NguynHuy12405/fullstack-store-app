import { Link } from 'react-router';
import { ArrowLeft, Search, Home } from 'lucide-react';

export default function NotFound404() {
  return (
    <div className="min-h-screen bg-white relative flex flex-col items-center justify-center overflow-hidden font-sans text-[#0a0d1a]">
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full select-none pointer-events-none">
        <h1 className="text-[30vw] font-serif font-bold text-[#f2f2f2] leading-none text-center opacity-80 mix-blend-multiply">
          404
        </h1>
      </div>
      <div className="relative z-10 container mx-auto px-4 text-center">
        <div className="w-16 h-1 bg-[#D2B48C] mx-auto mb-8"></div>
        <h2 className="text-3xl md:text-5xl font-serif italic mb-6">
          Out of Collection?
        </h2>
        
        <p className="max-w-md mx-auto text-gray-500 mb-10 font-light leading-relaxed">
          Trang bạn đang tìm kiếm dường như đã bị xóa hoặc không tồn tại trong "Bộ sưu tập" của chúng tôi.
        </p>

        <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
          
          <Link 
            to="/" 
            className="w-full sm:w-auto min-w-[200px] flex items-center justify-center gap-3 bg-[#0a0d1a] text-white py-4 px-8 uppercase text-xs font-bold tracking-[0.2em] hover:bg-[#D2B48C] hover:text-[#0a0d1a] transition-all duration-300 shadow-xl shadow-gray-200 hover:shadow-none"
          >
            <Home size={16} />
            Về Trang Chủ
          </Link>

          <button 
            onClick={() => window.history.back()}
            className="w-full sm:w-auto min-w-[200px] flex cursor-pointer items-center justify-center gap-3 bg-white border border-gray-200 text-[#0a0d1a] py-4 px-8 uppercase text-xs font-bold tracking-[0.2em] hover:border-[#0a0d1a] transition-all duration-300"
          >
            <ArrowLeft size={16} />
            Quay lại
          </button>
        </div>
        <div className="mt-16 pt-8 border-t border-gray-100 max-w-lg mx-auto">
            <p className="text-xs uppercase tracking-widest text-gray-400 mb-4">Hoặc tìm kiếm sản phẩm</p>
            <div className="relative group">
                <input 
                    type="text" 
                    placeholder="Tìm kiếm (ví dụ: Áo sơ mi, Blazer...)" 
                    className="w-full border-b border-gray-300 py-3 pr-10 text-sm focus:outline-none focus:border-[#0a0d1a] bg-transparent transition-colors placeholder:text-gray-300"
                />
                <Search className="absolute right-0 top-3 text-gray-400 group-focus-within:text-[#D2B48C] transition-colors" size={20} />
            </div>
        </div>
      </div>
      <div className="absolute bottom-8 w-full text-center">
        <span className="text-[10px] uppercase tracking-[0.3em] text-[#D2B48C]">
            Page Not Found &mdash; Error 404
        </span>
      </div>

    </div>
  );
};