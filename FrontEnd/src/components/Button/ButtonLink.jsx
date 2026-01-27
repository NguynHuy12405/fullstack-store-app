import { ArrowRight } from "lucide-react";
import { Link } from "react-router";

export default function ButtonLink({title, offIcon = true, onClick, to }) {
  return (
    <div className="pt-4">
        <button 
            onClick={onClick}
            type="submit" 
            className="group cursor-pointer relative px-8 py-4 bg-black text-white font-bold text-sm uppercase tracking-widest hover:bg-[#D2B48C] hover:text-black transition-all duration-300 overflow-hidden"
        >
          <Link to={to} className="relative z-10 flex items-center gap-2" >
            {title}
            {offIcon && <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform"/>}
          </Link>
        </button>
    </div>
  )
}
