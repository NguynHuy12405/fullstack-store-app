import { Link } from "react-router-dom";
import { useCartStore } from "../../stores/useCartStore";
import { CreditCard, ShoppingCart, Star } from "lucide-react";

export default function ProductCard({ product }) {
  const addToCart = useCartStore((state) => state.addToCart);

  const image = product.image || "/no-image.png";
  const rating = Number(product.rating) || 0;
  const reviewCount = product.reviewCount ?? 0;
  const price = Number(product.price) || 0;
  const discount = Number(product.discountPercentage) || 0;
  const salePrice = discount > 0 ? Math.round(price * (1 - discount / 100)) : price;

  return (
    <div className="bg-white rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-200 overflow-hidden flex flex-col group">

      {/* IMAGE */}
      <div className="relative overflow-hidden aspect-square">
        <img
          src={image}
          alt={product.name}
          className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
        />
        {discount > 0 && (
          <div className="absolute top-2 right-2 bg-black/80 px-2 py-1 rounded-md text-xs font-bold text-white shadow-sm">
            -{discount}%
          </div>
        )}
      </div>

      <div className="p-4 flex-1 flex flex-col">
        {/* CATEGORY */}
        <div className="text-xs text-gray-500 mb-1 uppercase tracking-wide">
          {product.category}
        </div>
        {/* NAME */}
        <h3 className="font-semibold text-gray-900 text-lg mb-2 line-clamp-2 transition-all">
          <Link
            className="font-semibold whitespace-nowrap hover:text-[#D2B48C]"
            to={`/products/${product.id}`}
          >
            {product.name}
          </Link>
        </h3>
        {/* RATING */}
        <div className="flex items-center gap-1 mb-3 text-sm">
          {Array.from({ length: 5 }).map((_, i) => (
            <Star
              key={i}
              className={`w-4 h-4 ${
                i < Math.round(rating)
                  ? "text-[#D2B48C] fill-[#D2B48C]"
                  : "text-gray-300"
              }`}
            />
          ))}
          <span className="text-gray-700 ml-1">{rating}</span>
          <span className="text-xs text-gray-400 ml-1">({reviewCount} đánh giá)</span>
        </div>
        {/* PRICE */}
        <div className="mt-auto">
          <div className="flex items-center gap-2 mb-4">
            <div className="text-xl font-bold text-black">
              {salePrice.toLocaleString("vi-VN")} ₫
            </div>
            {discount > 0 && (
              <div className="text-sm line-through text-gray-400">
                {price.toLocaleString("vi-VN")} ₫
              </div>
            )}
          </div>
          {/* BUTTONS */}
          <div className="grid grid-cols-2 gap-2">
            <button
              onClick={() => addToCart(product)}
              className="flex items-center justify-center gap-2 cursor-pointer
                bg-[#D2B48C] hover:bg-black text-black hover:text-white 
                py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-colors duration-300"
            >
              <CreditCard size={16} /> Mua ngay
            </button>

            <button
              onClick={() => addToCart(product)}
              className="flex items-center justify-center gap-2 cursor-pointer hover:bg-[#D2B48C] hover:text-black
                py-2 rounded-lg text-sm font-medium transition-colors duration-300"
            >
              <ShoppingCart size={16} /> Thêm
            </button>
          </div>

        </div>
      </div>
    </div>
  );
}
