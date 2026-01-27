
export default function StatItem({ count, label }) {
  return (
    <div className="flex flex-col items-center">
        <span className="text-lg font-bold text-[#0a0d1a]">{count}</span>
        <span className="text-[10px] text-gray-400 uppercase tracking-wider">{label}</span>
    </div>
  )
}
