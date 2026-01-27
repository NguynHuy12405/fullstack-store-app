import { Link, useNavigate } from "react-router";
import { useAuthStore } from "../../stores/useAuthStore";
import { useCartStore } from "../../stores/useCartStore";
import { ShoppingBag, Search, User } from "lucide-react";
import { useState } from "react";
import BtnAccount from "../Button/BtnAccount";

export default function NavBar() {
  const totalItems = useCartStore((state) => state.getTotalItems());
  const user = useAuthStore((s) => s.user);
  const [search, setSearch] = useState("");
  const navigate = useNavigate();

  const handleSearch = () => {
    if(search.trim()) {
        navigate(`/products?search=${search}`);
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter') handleSearch();
  };

  return (
    <header className="bg-white border-b border-gray-100 sticky top-0 z-40">
      <div className="container mx-auto px-4 md:px-8 h-20 flex justify-between items-center">
        <div className="w-1/4 md:w-1/3 flex justify-start">
          <Link to="/" className="group">
            <h1 className="text-2xl md:text-3xl font-serif font-bold tracking-tight text-[#0a0d1a]">
              ALIHAHA
              <span className="text-[#D2B48C] text-4xl leading-none">.</span>
            </h1>
            <span className="hidden md:block text-[10px] uppercase tracking-[0.3em] text-gray-400 group-hover:text-[#D2B48C] transition-colors">
                Fashion Store
            </span>
          </Link>
        </div>

        <div className="hidden md:flex w-1/3 justify-center">
          <div className="relative w-full max-w-md group">
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Search for items..."
              className="w-full py-2 bg-transparent border-b border-gray-300 text-sm text-[#0a0d1a] placeholder:text-gray-400 focus:outline-none focus:border-[#0a0d1a] transition-all duration-300"
            />
            <button
              onClick={handleSearch}
              className="absolute right-0 top-2 text-gray-400 group-hover:text-[#0a0d1a] transition-colors"
            >
              <Search size={18} strokeWidth={1.5} />
            </button>
          </div>
        </div>

        <div className="w-1/4 md:w-1/3 flex justify-end items-center gap-6 md:gap-8">
          <button className="md:hidden text-[#0a0d1a]">
            <Search size={20} strokeWidth={1.5} />
          </button>

          <div className="relative">
            {user ? (
              <BtnAccount />
            ) : (
              <Link
                to="/login"
                className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#0a0d1a] hover:text-[#D2B48C] transition-colors"
              >
                <User size={20} strokeWidth={1.5} />
                <span className="hidden md:inline">Login</span>
              </Link>
            )}
          </div>

          {/* Cart */}
          <Link to="/cart" className="relative group">
            <div className="text-[#0a0d1a] group-hover:text-[#D2B48C] transition-colors duration-300">
                <ShoppingBag size={20} strokeWidth={1.5} />
            </div>
            
            {totalItems > 0 && (
              <span className="absolute -right-2 -top-2 bg-[#0a0d1a] text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full group-hover:bg-[#D2B48C] group-hover:text-[#0a0d1a] transition-colors">
                {totalItems}
              </span>
            )}
          </Link>

        </div>
      </div>
    </header>
  );
}