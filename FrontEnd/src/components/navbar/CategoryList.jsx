import { useEffect } from "react";
import { Link } from "react-router-dom";
import { useCategoryStore } from "../../stores/useCategoryStore";
import { ChevronDown } from "lucide-react";

export default function CategoryList() {
  const { categories, loadCategories, loading } = useCategoryStore();

  useEffect(() => {
    loadCategories();
  }, []);

  return (
    <nav className="h-full hidden md:flex items-center ml-12">
      <ul className="flex items-center gap-8 h-full">
        {Object.keys(categories).map((group) => {
          const items = categories[group];
          if (!Array.isArray(items) || items.length === 0) return null;

          return (
            <li key={group} className="group h-full flex items-center relative">
              <Link
                to={`/products?group=${group}`}
                className="flex items-center gap-1 py-4 text-xs font-bold uppercase tracking-[0.2em] text-[#0a0d1a] hover:text-[#D2B48C] transition-colors relative"
              >
                {group}
                <ChevronDown size={10} className="opacity-40 group-hover:text-[#D2B48C]" />
                <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-[#D2B48C] transition-all duration-300 group-hover:w-full"></span>
              </Link>

              {/* 2. Mega Dropdown */}
              <div className="absolute left-0 top-full pt-2 opacity-0 invisible translate-y-4 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 ease-out z-50">
                <div className="bg-white border border-gray-100 shadow-xl min-w-[260px] p-6 relative">
                  <div className="absolute top-0 left-0 w-full h-0.5 bg-[#0a0d1a]" />

                  {loading && (
                    <div className="text-xs text-gray-400 uppercase tracking-wider">Loading...</div>
                  )}

                  {!loading && (
                    <div className="flex flex-col">
                      <span className="font-serif italic text-lg text-[#0a0d1a] mb-4 border-b border-gray-100 pb-2">
                        {group} Collection
                      </span>

                      <ul className="space-y-3">
                        {items.map((cate) => (
                          <li key={cate.id}>
                            <Link
                              to={`/category/${cate.slug}`}
                              className="block text-sm text-gray-600 hover:text-[#D2B48C] hover:translate-x-2 transition-all duration-300"
                            >
                              {cate.name}
                            </Link>
                          </li>
                        ))}
                      </ul>

                      <div className="mt-6 pt-4 border-t border-dashed border-gray-200">
                        <Link to={`/products?group=${group}`} className="text-[10px] font-bold uppercase tracking-widest text-[#0a0d1a] hover:underline">
                            View All Products
                        </Link>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </li>
          );
        })}
      </ul>
    </nav>
  );
}