
export default function InfoRow({ icon, label, value }) {
  return (
    <div className="flex items-center group">
        <div className="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-[#0a0d1a] group-hover:bg-[#D2B48C] group-hover:text-white transition-colors duration-300">
            {icon}
        </div>
        <div className="ml-4 flex-1">
            <p className="text-xs text-gray-400 uppercase tracking-wide mb-1">{label}</p>
            <p className="text-[#0a0d1a] font-medium">{value}</p>
        </div>
    </div>
  )
}
