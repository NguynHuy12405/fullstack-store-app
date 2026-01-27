import { Star, Upload, X, Image as ImageIcon } from "lucide-react";
import { useState } from "react";

export default function AddReviewForm({ onSubmit }) {
  const [rating, setRating] = useState(0);
  const [text, setText] = useState("");
  const [images, setImages] = useState([]);

  const handleImageUpload = (e) => {
    const files = [...e.target.files];
    const newImgs = files.map((file) => URL.createObjectURL(file));
    setImages([...images, ...newImgs]);
  };

  const removeImage = (index) => {
    const newImages = images.filter((_, i) => i !== index);
    setImages(newImages);
  };

  const submitForm = () => {
    if (rating === 0) return alert("Vui lòng chọn số sao đánh giá.");
    if (text.trim().length < 5) return alert("Vui lòng nhập nội dung đánh giá.");

    onSubmit({
      rating,
      comment: text,
      images,
      reviewerName: "Current User", // Nên lấy từ AuthStore thực tế
      reviewerEmail: "user@example.com",
    });

    // Reset form
    setRating(0);
    setText("");
    setImages([]);
  };

  return (
    <div className="bg-[#fcfcfc] border border-gray-100 p-8 md:p-10 h-full flex flex-col">
      <div className="mb-8 border-b border-gray-200 pb-6">
        <h3 className="text-3xl font-serif italic text-[#0a0d1a] mb-2">
          Write a Review
        </h3>
        <p className="text-xs text-gray-400 uppercase tracking-widest">
          Chia sẻ trải nghiệm của bạn về sản phẩm
        </p>
      </div>

      <div className="mb-8">
        <label className="block text-xs font-bold uppercase tracking-widest text-[#0a0d1a] mb-3">
            Đánh giá tổng quan
        </label>
        <div className="flex gap-2">
          {[1, 2, 3, 4, 5].map((s) => (
            <button
              key={s}
              onClick={() => setRating(s)}
              className="group focus:outline-none transition-transform active:scale-95"
            >
              <Star
                size={24}
                strokeWidth={1.5}
                className={`transition-colors duration-300 ${
                  s <= rating
                    ? "fill-[#FFD700] text-[#0a0d1a]"
                    : "fill-transparent text-gray-300 group-hover:text-[#D2B48C]"
                }`}
              />
            </button>
          ))}
        </div>
      </div>

      <div className="mb-8 grow">
        <label className="block text-xs font-bold uppercase tracking-widest text-[#0a0d1a] mb-3">
            Nội dung
        </label>
        <textarea
          rows={5}
          value={text}
          onChange={(e) => setText(e.target.value)}
          placeholder="Chất liệu vải thế nào? Form dáng có vừa vặn không?..."
          className="w-full bg-white border border-gray-200 p-4 text-sm text-[#0a0d1a] placeholder:text-gray-300 focus:border-[#0a0d1a] focus:outline-none transition-colors resize-none"
        />
      </div>

      <div className="mb-8">
        <label className="block text-xs font-bold uppercase tracking-widest text-[#0a0d1a] mb-3">
            Hình ảnh thực tế
        </label>
        
        <div className="flex flex-wrap gap-3">
            <label className="w-20 h-20 border border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:border-[#D2B48C] hover:bg-[#D2B48C]/5 transition-all group text-gray-400 hover:text-[#D2B48C]">
                <Upload size={20} strokeWidth={1.5} />
                <span className="text-[9px] uppercase font-bold mt-1">Upload</span>
                <input type="file" className="hidden" multiple accept="image/*" onChange={handleImageUpload} />
            </label>

            {images.map((img, i) => (
                <div key={i} className="relative w-20 h-20 group">
                    <img
                        src={img}
                        alt="preview"
                        className="w-full h-full object-cover border border-gray-100"
                    />
                    <button 
                        onClick={() => removeImage(i)}
                        className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white"
                    >
                        <X size={16} />
                    </button>
                </div>
            ))}
        </div>
      </div>

      <button
        onClick={submitForm}
        className="w-full mt-auto bg-[#0a0d1a] text-white py-4 uppercase text-xs font-bold tracking-[0.2em] hover:bg-[#D2B48C] hover:text-[#0a0d1a] transition-all duration-300 shadow-xl shadow-gray-100 hover:shadow-none"
      >
        Gửi đánh giá
      </button>
      
    </div>
  );
}