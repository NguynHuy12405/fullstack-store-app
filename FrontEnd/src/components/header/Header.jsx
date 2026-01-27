import { useEffect, useState } from "react";
import CategoryList from "../navbar/CategoryList";
import NavBar from "../navbar/NavBar";
import { X } from "lucide-react";
import NavList from "../list/NavList";

export default function Header() {
  const [showPromo, setShowPromo] = useState(true);
  const [isScrolled, setIsScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 0);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header className={`fixed w-full top-0 z-50 transition-all duration-300 bg-white ${
        isScrolled ? "shadow-md" : "shadow-none"
    }`}>
      {showPromo && (
        <div className="bg-[#0a0d1a] text-white text-[10px] md:text-xs font-bold uppercase tracking-widest py-2 px-4 relative transition-all">
          <div className="container mx-auto flex justify-between items-center">
            <div className="flex-1 text-center text-[10px] font-light">
                Miễn phí vận chuyển cho đơn hàng từ 500k - <span className="text-[#D2B48C] cursor-pointer hover:underline">Mua ngay</span>
            </div>
            <button 
                onClick={() => setShowPromo(false)} 
                className="absolute right-4 text-gray-400 hover:text-white"
            >
                <X size={14} />
            </button>
          </div>
        </div>
      )}

      <NavBar />

      <div className="hidden md:block border-b border-gray-100 bg-white">
        <div className="container mx-auto px-4 md:px-8 h-12 flex items-center justify-center">
          {/* <CategoryList /> */}
          <NavList />
        </div>
      </div>

    </header>
  );
}