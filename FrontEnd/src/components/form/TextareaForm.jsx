
export default function TextareaForm({ label, ...rest }) {
  return (
    <div>
      <label className="block text-sm font-medium mb-1">{label}</label>
      <textarea {...rest} rows="3" className="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
    </div>
  )
}
