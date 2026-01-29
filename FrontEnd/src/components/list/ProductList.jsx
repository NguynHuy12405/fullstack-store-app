import { useEffect, useState } from "react";
import { useProductStore } from "../../stores/useProductStore";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowLeft, ArrowRight } from "lucide-react";
import ProductCard from "../card/ProductCard";

export default function ProductList({ titleProducts }) {
  const { products, loading, loadProducts } = useProductStore();

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
  }, [loadProducts]);

  if (loading || products.length === 0) return null;

  const maxIndex = Math.max(0, products.length - itemsPerPage);

  const next = () =>
    setCurrentIndex((prev) => (prev < maxIndex ? prev + 1 : 0));

  const prev = () =>
    setCurrentIndex((prev) => (prev > 0 ? prev - 1 : maxIndex));

  const visibleProducts = products.slice(
    currentIndex,
    currentIndex + itemsPerPage
  );

  return (
    <section className="pb-12 bg-white border-b border-gray-100">
      <div className="w-full">
        {/* HEADER */}
        <div className="flex justify-between items-end mb-10 px-4 md:px-0">
          <div>
            <div className="w-8 h-0.5 bg-[#D2B48C] mb-4"></div>
            <h2 className="text-3xl md:text-4xl font-serif italic">
              {titleProducts}
            </h2>
          </div>

          <div className="flex gap-2">
            <button
              onClick={prev}
              disabled={products.length <= itemsPerPage}
              className="w-10 h-10 border hover:bg-black hover:text-white transition"
            >
              <ArrowLeft size={18} />
            </button>
            <button
              onClick={next}
              disabled={products.length <= itemsPerPage}
              className="w-10 h-10 border hover:bg-black hover:text-white transition"
            >
              <ArrowRight size={18} />
            </button>
          </div>
        </div>

        {/* PRODUCTS */}
        <div className="overflow-hidden px-4 md:px-0">
          <div
            className={`grid gap-6 ${
              ["grid-cols-2", "grid-cols-3", "grid-cols-4", "grid-cols-5"][
                itemsPerPage - 1
              ]
            }`}
          >
            <AnimatePresence mode="popLayout">
              {visibleProducts.map((product) => (
                <motion.div
                  key={product.id}
                  layout
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: -20 }}
                  transition={{ duration: 0.4 }}
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
