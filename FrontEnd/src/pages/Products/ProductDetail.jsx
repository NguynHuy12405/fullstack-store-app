import { useEffect, useState } from "react";
import { Star, ShoppingBag, Heart, Minus, Plus, Truck, ShieldCheck, ArrowLeft, Share2, ZoomIn, X } from "lucide-react";
import { useProductStore } from "../../stores/useProductStore";
import { useParams, Link } from "react-router";
import ReviewSection from "../../components/reviews/ReviewSection";
import AddReviewForm from "../../components/form/AddReviewForm";

export default function ProductDetail() {
  const detail = useProductStore((s) => s.productDetail);
  const loadProductDetail = useProductStore((s) => s.loadProductDetail);
  const addReview = useProductStore((s) => s.addReview);

  const [isZoomOpen, setIsZoomOpen] = useState(false);
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const { id } = useParams();

  const handleQuantity = (type) => {
    if (type === "dec" && quantity > 1) setQuantity(quantity - 1);
    if (type === "inc") setQuantity(quantity + 1);
  };

  useEffect(() => {
    loadProductDetail(Number(id));
  }, [id]);

  if (!detail) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-white">
        <div className="animate-pulse text-[#D2B48C] tracking-widest uppercase">Loading Product...</div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-white text-[#0a0d1a] font-sans pb-20">
      <div className="pt-8 pb-4 px-4 md:px-8 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur-md z-30">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
            <Link to="/" className="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-[#0a0d1a] transition-colors">
                <ArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform"/>
                Back to Collection
            </Link>
            <button className="text-gray-400 hover:text-[#D2B48C] transition-colors cursor-pointer">
                <Share2 size={20} strokeWidth={1.5} />
            </button>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 md:px-8 pt-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-20">
          {/* 2. IMAGE GALLERY */}
          <div className="lg:col-span-7 flex flex-col gap-6">
            <div className="relative bg-gray-50 aspect-1 overflow-hidden group cursor-zoom-in" onClick={() => setIsZoomOpen(true)}>
                <img
                    src={detail.images[selectedImage]}
                    alt={detail.name}
                    className="w-full h-auto object-cover transition-transform duration-700 ease-in-out group-hover:scale-105"
                />
                <div className="absolute bottom-4 right-4 bg-white/80 backdrop-blur px-3 py-2 flex items-center gap-2 text-xs font-bold uppercase tracking-wider opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <ZoomIn size={14} /> Zoom
                </div>
            </div>

            {/* Thumbnails */}
            <div className="grid grid-cols-4 sm:grid-cols-6 gap-4">
              {detail.images.map((img, idx) => (
                <button
                  key={idx}
                  onClick={() => setSelectedImage(idx)}
                  className={`aspect-square overflow-hidden bg-gray-50 transition-all duration-300 relative ${
                    selectedImage === idx ? "opacity-100 ring-1 ring-[#0a0d1a]" : "opacity-60 hover:opacity-100"
                  }`}
                >
                  <img src={img} alt={`thumb-${idx}`} className="w-full h-full object-cover" />
                  {selectedImage === idx && <div className="absolute inset-0 bg-[#0a0d1a]/5"></div>}
                </button>
              ))}
            </div>
          </div>

          <div className="lg:col-span-5 relative">
            <div className="sticky top-24">
                {/* Category / Rating */}
                <div className="flex items-center justify-between mb-4">
                    <span className="text-[#D2B48C] text-xs font-bold uppercase tracking-[0.2em]">New Arrival</span>
                    <div className="flex items-center gap-1 text-gray-400">
                        <Star size={14} className="text-[#0a0d1a] fill-[#0a0d1a]" />
                        <span className="text-xs font-medium text-[#0a0d1a]">{detail.rating}</span>
                        <span className="text-[10px] ml-1">({detail.reviewCount} reviews)</span>
                    </div>
                </div>

                {/* Title */}
                <h1 className="text-4xl md:text-5xl font-serif font-medium leading-tight mb-4 text-[#0a0d1a]">
                    {detail.name}
                </h1>

                {/* Price */}
                <div className="flex items-baseline gap-4 mb-8">
                    <span className="text-2xl font-serif text-[#0a0d1a]">
                        {detail.price.toLocaleString("vi-VN")}₫
                    </span>
                    {detail.originalPrice && (
                         <span className="text-lg text-gray-400 line-through font-light decoration-gray-300">
                            {detail.originalPrice.toLocaleString("vi-VN")}₫
                        </span>
                    )}
                </div>

                {/* Description */}
                <p className="text-gray-500 font-light leading-relaxed mb-10 text-sm md:text-base border-t border-gray-100 pt-6">
                    {detail.description}
                </p>

                <div className="space-y-6">
                    {/* Quantity Selector (Minimalist) */}
                    <div className="flex items-center justify-between border border-gray-200 p-4">
                        <span className="text-xs font-bold uppercase tracking-widest text-gray-400">Quantity</span>
                        <div className="flex items-center gap-6">
                            <button onClick={() => handleQuantity("dec")} className="hover:text-[#D2B48C] transition-colors"><Minus size={16}/></button>
                            <span className="w-4 text-center text-sm font-medium">{quantity}</span>
                            <button onClick={() => handleQuantity("inc")} className="hover:text-[#D2B48C] transition-colors"><Plus size={16}/></button>
                        </div>
                    </div>

                    {/* Buttons */}
                    <div className="flex gap-4">
                        <button className="flex-1 bg-[#0a0d1a] text-white h-14 uppercase text-xs font-bold tracking-[0.2em] hover:bg-[#D2B48C] hover:text-[#0a0d1a] transition-all duration-300 flex items-center justify-center gap-3">
                            <ShoppingBag size={18} /> Add to Cart
                        </button>
                        <button className="w-14 h-14 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition-all duration-300">
                            <Heart size={20} />
                        </button>
                    </div>
                </div>

                {/* Policies */}
                <div className="mt-10 space-y-4 pt-8 border-t border-gray-100">
                    <div className="flex items-start gap-3">
                        <Truck size={18} className="text-[#D2B48C] mt-0.5" />
                        <div>
                            <p className="text-xs font-bold uppercase tracking-wide mb-1">Free Delivery</p>
                            <p className="text-xs text-gray-400">Miễn phí vận chuyển cho đơn hàng trên 500k.</p>
                        </div>
                    </div>
                    <div className="flex items-start gap-3">
                        <ShieldCheck size={18} className="text-[#D2B48C] mt-0.5" />
                        <div>
                            <p className="text-xs font-bold uppercase tracking-wide mb-1">Authentic Guarantee</p>
                            <p className="text-xs text-gray-400">Hoàn tiền 200% nếu phát hiện hàng giả.</p>
                        </div>
                    </div>
                </div>

            </div>
          </div>
        </div>
        
        {/* 4. REVIEWS SECTION */}
        <div className="mt-24 pt-16 border-t border-gray-200">
            <h2 className="text-2xl font-serif italic text-center mb-12">Client Reviews</h2>
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div className="lg:col-span-1">
                    <AddReviewForm onSubmit={addReview} />
                </div>
                <div className="lg:col-span-2">
                    <ReviewSection reviews={detail.reviews} />
                </div>
            </div>
        </div>
      </div>

      {isZoomOpen && (
        <div className="fixed inset-0 z-50 bg-[#0a0d1a]/95 backdrop-blur-sm flex items-center justify-center animate-in fade-in duration-300">
          <button 
            onClick={() => setIsZoomOpen(false)} 
            className="absolute top-6 right-6 text-white hover:text-[#D2B48C] transition-colors p-2"
          >
            <X size={32} strokeWidth={1} />
          </button>
          
          <div className="w-full h-full p-4 md:p-10 flex items-center justify-center">
            <img 
              src={detail.images[selectedImage]} 
              className="max-w-full max-h-full object-contain shadow-2xl"
              alt="Zoomed product"
            />
          </div>
        </div>
      )}

    </div>
  );
}