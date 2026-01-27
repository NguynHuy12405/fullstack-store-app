export default function SelectForm({ label, name, value, options, onChange }) {
  return (
    <div className="space-y-1">
      <label className="text-sm font-medium text-gray-700">{label}</label>
      <select
        name={name}
        value={value || ""}
        onChange={(e) => onChange(name, e.target.value)}
        className="w-full px-4 py-2 border rounded-lg text-sm cursor-pointer"
      >
        <option value="">-- Chọn {label} --</option>
        {options.map((opt) => (
          <option key={opt.value} value={opt.value}>
            {opt.label}
          </option>
        ))}
      </select>
    </div>
  );
}
