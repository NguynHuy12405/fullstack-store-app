import { Link } from "react-router-dom";

export default function MenuItem({ to, icon, label, onClick, isDanger = false, setOpen }) {
  const content = (
      <div className={`flex items-center gap-3 px-6 py-3 text-xs font-bold uppercase tracking-widest transition-colors duration-200 
        ${isDanger 
          ? "text-red-500 hover:bg-red-50" 
          : "text-[#0a0d1a] hover:bg-[#faf9f7] hover:text-[#D2B48C]"
        }`}
      >
        {icon}
        {label}
      </div>
    );

    if (onClick) {
      return (
        <button onClick={onClick} className="w-full text-left block border-b border-gray-50 last:border-0">
          {content}
        </button>
      );
    }

    return (
      <Link to={to} className="block border-b border-gray-50 last:border-0" onClick={() => setOpen(false)}>
        {content}
      </Link>
    );
}
