import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

export default function SectionHeader({ title, subtitle, linkTo }) {
  return (
    <div className="flex flex-col md:flex-row justify-between items-end mb-12 px-4 border-b border-gray-100 pb-4">
      <div className="text-center md:text-left">
        {subtitle && (
          <span className="text-[#D2B48C] text-xs font-bold uppercase tracking-[0.2em] mb-2 block">
            {subtitle}
          </span>
        )}
        <h2 className="text-3xl md:text-4xl font-serif italic text-[#0a0d1a]">
          {title}
        </h2>
      </div>
      
      {linkTo && (
        <Link 
          to={linkTo} 
          className="hidden md:flex items-center gap-2 text-xs font-bold uppercase tracking-widest hover:text-[#D2B48C] transition-colors mt-4 md:mt-0"
        >
          View All <ArrowRight size={16} />
        </Link>
      )}
    </div>
  )
}
