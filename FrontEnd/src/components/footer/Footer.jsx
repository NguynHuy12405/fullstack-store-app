import { Link } from "react-router-dom";
import { Facebook, Instagram, Mail, Phone, MapPin } from "lucide-react";

export default function Footer() {
  return (
    <footer className="bg-[#0f0f0f] text-[#e5e5e5] pt-20 pb-12 border-t border-[#D2B48C]/20">
      <div className="max-w-7xl mx-auto px-6">
        {/* GRID */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-14 mb-16">
          
          {/* Brand */}
          <div>
            <h3 className="text-2xl font-light tracking-wider mb-4 text-white">
              CC STORE
            </h3>
            <p className="text-sm text-gray-400 leading-relaxed">
              Fashion boutique • Minimal & Luxury  
              <br />Wear your confidence.
            </p>

            {/* Social */}
            <div className="flex gap-4 mt-6">
              {[Facebook, Instagram].map((Icon, i) => (
                <a
                  key={i}
                  className="w-10 h-10 flex items-center justify-center border border-[#D2B48C]/30 
                             rounded-full hover:bg-[#D2B48C]/20 transition"
                >
                  <Icon size={18} />
                </a>
              ))}
            </div>
          </div>

          {/* Menu */}
          <div>
            <h4 className="text-lg font-medium mb-4 text-white tracking-wide">Khám phá</h4>
            <ul className="space-y-3 text-gray-400">
              <li><Link to="/" className="hover:text-white transition">Sản phẩm</Link></li>
              <li><Link to="/" className="hover:text-white transition">Bộ sưu tập</Link></li>
              <li><Link to="/" className="hover:text-white transition">Ưu đãi</Link></li>
              <li><Link to="/" className="hover:text-white transition">Lookbook</Link></li>
            </ul>
          </div>

          {/* Support */}
          <div>
            <h4 className="text-lg font-medium mb-4 text-white tracking-wide">Hỗ trợ</h4>
            <ul className="space-y-3 text-gray-400">
              <li><Link to="/contact-us" className="hover:text-white transition">Liên hệ</Link></li>
              <li><Link to="/" className="hover:text-white transition">FAQ</Link></li>
              <li><Link to="/" className="hover:text-white transition">Chính sách</Link></li>
              <li><Link to="/" className="hover:text-white transition">Bảo hành</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-lg font-medium mb-4 text-white tracking-wide">Liên hệ</h4>
            <ul className="space-y-3 text-gray-400 text-sm">
              <li className="flex items-start gap-3">
                <Mail size={18} /> info@ccstore.com
              </li>
              <li className="flex items-start gap-3">
                <Phone size={18} /> 0123 456 789
              </li>
              <li className="flex items-start gap-3">
                <MapPin size={18} /> Hồ Chí Minh, Việt Nam
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom */}
        <div className="border-t border-[#D2B48C]/20 pt-6 text-center">
          <p className="text-gray-500 text-sm tracking-wide">
            © 2024 CC Store — All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
}
