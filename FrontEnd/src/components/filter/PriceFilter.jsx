import {option_Price} from "../../mockData/data";

export default function PriceFilter({ priceRange, setPriceRange }) {
  return (
    <div>
        <h3 className="font-semibold text-gray-800 mb-3">
            Khoảng giá
        </h3>
        <div className="space-y-3">
            <div className="flex gap-2 items-center min-w-0">
                <input
                    type="number"
                    placeholder="Từ"
                    value={priceRange.min}
                    onChange={(e) =>
                        setPriceRange((p) => ({
                            ...p,
                            min: e.target.value,
                        }))
                    }
                    className="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-lg text-sm 
                            focus:ring-2 focus:ring-[#D2B48C] outline-none"
                />

                <span className="text-gray-400">—</span>

                <input
                    type="number"
                    placeholder="Đến"
                    value={priceRange.max}
                    onChange={(e) =>
                        setPriceRange((p) => ({
                            ...p,
                            max: e.target.value,
                        }))
                    }
                    className="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-lg text-sm 
                            focus:ring-2 focus:ring-[#D2B48C] outline-none"
                />
            </div>

            <div className="grid grid-cols-2 gap-2">
                {option_Price.map((range, idx) => (
                    <button
                        key={idx}
                        onClick={() =>
                            setPriceRange({
                                min: range.min || "",
                                max: range.max || "",
                            })
                        }
                        className="text-xs py-2 px-3 border border-gray-300 rounded-lg 
                                    hover:border-[#D2B48C] hover:text-[#D2B48C] transition"
                    >
                        {range.label}
                    </button>
                ))}
            </div>

            <button className="w-full bg-[#D2B48C] text-black font-medium py-2.5 rounded-lg hover:bg-black hover:text-white transition cursor-pointer">
                Áp dụng giá
            </button>
        </div>
    </div>
  )
}
