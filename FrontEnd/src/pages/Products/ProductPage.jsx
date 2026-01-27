import { useEffect, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { Filter, ChevronDown, LayoutGrid, List } from "lucide-react";
import { useProductStore } from "../../stores/useProductStore";
import SideBarFilter from "../../components/filter/SideBarFilter";
import Pagination from "../../components/pagination/Pagination";
import ProductCard from "../../components/card/ProductCard";

export default function ProductPage() {
  const {
    products,
    loadProducts,
    page,
    limit,
    total,
    setPage,
    loading,
  } = useProductStore();
  const [isSidebarOpen, setSidebarOpen] = useState(false);

  useEffect(() => {
    loadProducts({ page, limit });
  }, [page, limit]);

  const totalPages = limit ? Math.ceil(total / limit) : 0;

  const handlePaginate = (pageNumber) => {
    setPage(pageNumber);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <div className="min-h-screen bg-white text-[#0a0d1a] font-sans">
      <div className="bg-[#f8f8f8] py-16 mb-12 border-b border-gray-400" style={{
          backgroundImage: "url('./img/banner4.jpg')",
          width: "100%",
          height: "100%",
          objectFit: "cover"
        }}>
        <div className="container mx-auto px-4 text-center">
            <h1 className="text-4xl md:text-5xl font-serif italic mb-4 font-black">
                The Collection
            </h1>
            <p className="text-xs uppercase tracking-[0.3em] text-gray-200">
                Fall Winter 2024
            </p>
        </div>
      </div>

      <div className="container mx-auto px-4 pb-20">
        <div className="flex flex-col lg:flex-row gap-12">
          <aside className={`lg:w-1/4 shrink-0 space-y-8 ${isSidebarOpen ? 'block' : 'hidden lg:block'}`}>
            <div className="sticky top-24">
                <SideBarFilter />
            </div>
          </aside>

          <main className="w-full lg:w-3/4">
            <div className="flex flex-wrap justify-between items-end mb-8 border-b border-gray-100 pb-4">
              <div className="flex items-center gap-4">
                 <button 
                    onClick={() => setSidebarOpen(!isSidebarOpen)}
                    className="lg:hidden flex items-center gap-2 text-xs font-bold uppercase tracking-widest border border-gray-200 px-3 py-2"
                 >
                    <Filter size={14} /> Filter
                 </button>
                 <span className="text-xs text-gray-400 uppercase tracking-widest">
                    Showing <span className="text-[#0a0d1a] font-bold">{products.length}</span> of {total} results
                 </span>
              </div>

              <div className="flex items-center gap-3 relative group cursor-pointer">
                 <span className="text-sm text-gray-500 hidden sm:inline">Sort by:</span>
                 <div className="relative">
                    <select className="appearance-none bg-transparent border-none pr-8 py-1 text-sm font-bold uppercase tracking-wide focus:ring-0 cursor-pointer text-[#0a0d1a]">
                        <option value="newest">Newest Arrivals</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="best">Best Sellers</option>
                    </select>
                    <ChevronDown size={14} className="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none text-[#D2B48C]" />
                 </div>
              </div>
            </div>

            {loading ? (
              <div className="min-h-[400px] flex items-center justify-center">
                  <span className="animate-pulse text-[#D2B48C] uppercase tracking-widest text-xs font-bold">Loading Collection...</span>
              </div>
            ) : (
                <>
                    {products.length === 0 ? (
                        <div className="text-center py-20 bg-gray-50">
                            <p className="text-gray-500 font-light italic">No products found matching your selection.</p>
                            <button onClick={() => window.location.reload()} className="mt-4 text-xs font-bold uppercase border-b border-black">Clear Filters</button>
                        </div>
                    ) : (
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-12">
                            <AnimatePresence>
                              {products.map((product, index) => (
                                  <motion.div
                                      key={product.id}
                                      initial={{ opacity: 0, y: 20 }}
                                      animate={{ opacity: 1, y: 0 }}
                                      exit={{ opacity: 0, y: 20 }}
                                      transition={{ duration: 0.4, delay: index * 0.05, ease: "easeOut" }}
                                  >
                                  <ProductCard product={product} />
                                  </motion.div>
                              ))}
                            </AnimatePresence>
                        </div>
                    )}
                </>
            )}

            <div className="mt-16 pt-8 border-t border-gray-100 flex justify-center">
                {totalPages > 1 && (
                    <Pagination
                        totalPages={totalPages}
                        currentPage={page}
                        onPageChange={handlePaginate}
                    />
                )}
            </div>

          </main>
        </div>
      </div>
    </div>
  );
}