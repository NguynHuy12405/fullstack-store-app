import { useState } from "react";
import { Minus, Plus } from "lucide-react";

export default function FilterSection({ title, defaultOpen = true, children }) {
  const [isOpen, setIsOpen] = useState(defaultOpen);

  return (
    <div className="border-b border-gray-100 py-5">
      <button
        onClick={() => setIsOpen(o => !o)}
        className="w-full flex items-center justify-between group"
      >
        <span className="text-sm font-bold uppercase tracking-widest text-[#0a0d1a]">
          {title}
        </span>
        <span className="text-gray-400 group-hover:text-[#D2B48C] transition-colors">
          {isOpen ? <Minus size={16} /> : <Plus size={16} />}
        </span>
      </button>

      <div
        className={`overflow-hidden transition-all duration-300 ease-in-out ${
          isOpen ? "max-h-[500px] opacity-100 mt-5" : "max-h-0 opacity-0 mt-0"
        }`}
      >
        {children}
      </div>
    </div>
  );
}
