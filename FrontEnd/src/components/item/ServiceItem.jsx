
export default function ServiceItem({ icon, title, desc }) {
  return (
    <div className="flex flex-col items-center text-center group">
        <div className="mb-3 text-[#0a0d1a] group-hover:text-[#D2B48C] transition-colors">
            {icon}
        </div>
        <h4 className="text-xs font-bold uppercase tracking-widest mb-1">{title}</h4>
        <p className="text-[10px] text-gray-500 uppercase tracking-wide">{desc}</p>
    </div>
  )
}
