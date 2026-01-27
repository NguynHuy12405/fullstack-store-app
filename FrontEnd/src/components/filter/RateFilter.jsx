import { Star } from "lucide-react";

export default function RateFilter({ selectedRating, setSelectedRating }) {
  return (
    <div>
        <h3 className="font-semibold text-gray-800 mb-3">
            Đánh giá
        </h3>
        <div className="space-y-2">
            {[5, 4, 3, 2, 1].map((star) => (
                <label
                    key={star}
                    className={`flex items-center gap-2 p-2 rounded-lg cursor-pointer transition-all ${
                        selectedRating === star
                            ? "bg-[#F2E6D7] border border-[#D2B48C]"
                            : "hover:bg-gray-50 border border-transparent"
                    }`}
                >
                    <input
                        type="radio"
                        name="rating"
                        value={star}
                        checked={selectedRating === star}
                        onChange={() => setSelectedRating(star)}
                        className="peer hidden"
                    />
                    <div className="w-4 h-4 rounded-full border border-gray-500 bg-gray-200 
                                    peer-checked:bg-[#D2B48C] peer-checked:border-black transition" />

                    <div className="flex items-center gap-1.5">
                        <div className="flex">
                            {[...Array(5)].map((_, i) => (
                                <Star
                                    key={i}
                                    size={16}
                                    className={
                                        i < star
                                            ? "text-[#D2B48C] fill-[#D2B48C]"
                                            : "text-gray-300"
                                    }
                                />
                            ))}
                        </div>
                        <span className="text-sm text-gray-700">
                            trở lên
                        </span>
                    </div>
                </label>
            ))}
        </div>
    </div>
  )
}
