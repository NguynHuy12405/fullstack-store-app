import { useEffect, useState } from "react";
import { useProductStore } from "../../stores/useProductStore";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowLeft, ArrowRight } from "lucide-react";
import ProductCard from "../card/ProductCard";

export default function ProductList({ titleProducts }) {
  const { products, loadProducts } = useProductStore();
  const [currentIndex, setCurrentIndex] = useState(0);
  const [itemsPerPage, setItemsPerPage] = useState(4);

  useEffect(() => {
    loadProducts();

    const handleResize = () => {
      if (window.innerWidth < 640) setItemsPerPage(1);      
      else if (window.innerWidth < 1024) setItemsPerPage(2); 
      else if (window.innerWidth < 1280) setItemsPerPage(3); 
      else setItemsPerPage(4);                             
    };

    handleResize();

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const maxIndex = Math.max(0, products.length - itemsPerPage);
  
  const next = () => {
    if (currentIndex < maxIndex) {
      setCurrentIndex((prev) => prev + 1);
    } else {
        setCurrentIndex(0); 
    }
  };

  const prev = () => {
    if (currentIndex > 0) {
      setCurrentIndex((prev) => prev - 1);
    } else {
        setCurrentIndex(maxIndex);
    }
  };

  const visibleProducts = products.slice(currentIndex, currentIndex + itemsPerPage);

  return (
    <section className="pb-12 bg-white text-[#0a0d1a] border-b border-gray-100 last:border-0">
      <div className="w-full">
        <div className="flex flex-row justify-between items-end mb-10 px-4 md:px-0">
          <div>
             <div className="w-8 h-0.5 bg-[#D2B48C] mb-4"></div>
             <h2 className="text-3xl md:text-4xl font-serif italic text-[#0a0d1a] leading-none">
                {titleProducts}
             </h2>
          </div>

          <div className="flex gap-2">
            <button
              onClick={prev}
              disabled={products.length <= itemsPerPage}
              className="w-10 h-10 flex items-center justify-center border border-[#0a0d1a]/20 hover:border-[#0a0d1a] hover:bg-[#0a0d1a] hover:text-white transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed group"
            >
              <ArrowLeft size={18} strokeWidth={1.5} />
            </button>
            <button
              onClick={next}
              disabled={products.length <= itemsPerPage}
              className="w-10 h-10 flex items-center justify-center border border-[#0a0d1a]/20 hover:border-[#0a0d1a] hover:bg-[#0a0d1a] hover:text-white transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed group"
            >
              <ArrowRight size={18} strokeWidth={1.5} />
            </button>
          </div>
        </div>

        <div className="overflow-hidden px-4 md:px-0">
            <div className={`grid gap-6 ${
                itemsPerPage === 1 ? 'grid-cols-1' : 
                itemsPerPage === 2 ? 'grid-cols-2' : 
                itemsPerPage === 3 ? 'grid-cols-3' : 
                'grid-cols-4'
            }`}>
            <AnimatePresence mode="popLayout" initial={false}>
                {visibleProducts.map((product) => (
                <motion.div
                    key={product.id}
                    layout
                    initial={{ opacity: 0, x: 20 }}
                    animate={{ opacity: 1, x: 0 }}
                    exit={{ opacity: 0, x: -20 }}
                    transition={{ duration: 0.4, ease: [0.25, 1, 0.5, 1] }} // Cubic bezier cho mượt
                >
                    <ProductCard product={product} />
                </motion.div>
                ))}
            </AnimatePresence>
            </div>
        </div>

      </div>
    </section>
  );
}