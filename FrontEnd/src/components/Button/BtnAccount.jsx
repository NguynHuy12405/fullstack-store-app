import { User, LogOut, Settings, ShoppingBag, LayoutDashboard } from "lucide-react";
import { useState, useEffect, useRef } from "react";
import { useAuthStore } from "../../stores/useAuthStore";
import MenuItem from "../item/MenuItem";

export default function BtnAccount() {
  const user = useAuthStore((s) => s.user);
  const logout = useAuthStore((state) => state.logout);
  const [open, setOpen] = useState(false);
  const menuRef = useRef(null);

  useEffect(() => {
    function handleClickOutside(event) {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  return (
    <div className="relative" ref={menuRef}>
      <button
        onClick={() => setOpen(!open)}
        className={`flex items-center gap-2 transition-all duration-300 group ${
          open ? "text-[#D2B48C]" : "text-[#0a0d1a] hover:text-[#D2B48C]"
        }`}
      >
        <User size={20} strokeWidth={1.5} />
        <span className="hidden md:block text-xs font-bold uppercase tracking-wider max-w-[100px] truncate">
            {user?.name?.split(' ')[0] || "Account"}
        </span>
      </button>

      <div
        className={`absolute right-0 top-full mt-4 w-64 bg-white border border-gray-100 shadow-xl z-50 transform transition-all duration-200 origin-top-right ${
          open
            ? "opacity-100 visible translate-y-0"
            : "opacity-0 invisible -translate-y-2"
        }`}
      >
        <div className="px-6 py-4 border-b border-gray-100 bg-[#faf9f7]">
            <p className="text-xs text-gray-400 uppercase tracking-widest mb-1">Signed in as</p>
            <p className="text-sm font-serif italic text-[#0a0d1a] truncate">{user.email}</p>
        </div>

        <div className="py-2">
            {user.role === "Admin" && (
                <MenuItem 
                    setOpen={setOpen}
                    to="/admin" 
                    icon={<LayoutDashboard size={16} />} 
                    label="Admin Dashboard" 
                />
            )}

            <MenuItem 
                setOpen={setOpen}
                to="/profile" 
                icon={<Settings size={16} />} 
                label="Profile & Settings" 
            />
            
            <MenuItem 
                setOpen={setOpen}
                to="/orders" 
                icon={<ShoppingBag size={16} />} 
                label="My Orders" 
            />
            
            <MenuItem 
                setOpen={setOpen}
                onClick={logout} 
                icon={<LogOut size={16} />} 
                label="Log Out" 
                isDanger 
            />
        </div>
      </div>
    </div>
  );
}