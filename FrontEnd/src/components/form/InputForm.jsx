import React from 'react'

export default function InputForm({ label, ...rest }) {
  return (
    <div>
      <label className="block text-sm font-medium mb-1">{label}</label>
      <input {...rest} className="w-full bg-transparent border-b border-gray-300 py-3 text-sm focus:border-[#0a0d1a] focus:outline-none transition-colors placeholder:text-gray-400 text-[#0a0d1a]" />
    </div>
  )
}
