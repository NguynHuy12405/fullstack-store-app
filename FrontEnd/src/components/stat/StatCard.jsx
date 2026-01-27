
export default function StatCard({ label, value, icon: Icon, color }) {
  return (
    <div className="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div className={`p-3 rounded-lg ${color}`}>
            <Icon size={20} className="text-white" />
        </div>
        <div>
            <p className="text-gray-500 text-xs uppercase font-medium">{label}</p>
            <h3 className="text-xl font-bold text-gray-800 mt-1">{value}</h3>
        </div>
    </div>
  )
}
