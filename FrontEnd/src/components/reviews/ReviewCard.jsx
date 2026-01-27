import { useState } from "react";
import { Star, Quote } from "lucide-react";

export default function ReviewCard({ review }) {
  const [expanded, setExpanded] = useState(false);
  const hasLongText = review.comment.length > 150;

  return (
    <div className="group flex flex-col h-full bg-white border border-gray-100 p-8 hover:border-[#0a0d1a] transition-colors duration-300 relative">
      <Quote 
        size={40} 
        className="text-[#f0f0f0] group-hover:text-[#D2B48C]/20 transition-colors absolute top-6 right-6" 
      />

      <div className="flex items-center gap-4 mb-6">
        <div className="w-12 h-12 overflow-hidden bg-gray-100">
           <img
            src={review.avatar || `https://ui-avatars.com/api/?name=${review.reviewerName}&background=0a0d1a&color=fff`}
            alt={review.reviewerName}
            className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
          />
        </div>
        
        <div>
          <h4 className="font-serif text-lg font-medium text-[#0a0d1a] leading-none mb-2">
            {review.reviewerName}
          </h4>

          <div className="flex gap-0.5">
            {Array(5).fill(0).map((_, i) => (
              <Star
                key={i}
                size={12}
                fill={i < review.rating ? "#FFD700" : "transparent"}
                stroke={i < review.rating ? "#0a0d1a" : "#d1d5db"}
                strokeWidth={1.5}
              />
            ))}
          </div>
        </div>
      </div>

      <div className="grow">
        <p className="text-gray-500 text-sm font-light leading-relaxed italic">
            &quot;
            {expanded || !hasLongText
                ? review.comment
                : review.comment.slice(0, 150) + "..."}
            &quot;
        </p>
        
        {hasLongText && (
            <button
            className="mt-3 text-[10px] font-bold uppercase tracking-widest text-[#0a0d1a] border-b border-[#0a0d1a] pb-0.5 hover:text-[#D2B48C] hover:border-[#D2B48C] transition-all"
            onClick={() => setExpanded(!expanded)}
            >
            {expanded ? "Thu gọn" : "Đọc tiếp"}
            </button>
        )}
      </div>

      {review.images && review.images.length > 0 && (
        <div className="grid grid-cols-3 gap-2 mt-6 pt-6 border-t border-dashed border-gray-100">
          {review.images.slice(0, 3).map((img, i) => (
            <div
              key={i}
              className="aspect-square bg-gray-50 overflow-hidden cursor-pointer relative group/img"
            >
              <div className="absolute inset-0 bg-black/0 group-hover/img:bg-black/10 transition-colors z-10"></div>
              <img
                src={img}
                alt="review-img"
                className="w-full h-full object-cover"
              />
            </div>
          ))}
        </div>
      )}

      <div className="mt-6 text-[10px] text-gray-300 uppercase tracking-widest font-medium text-right">
         {review.date || "Verified Purchase"}
      </div>

    </div>
  );
}