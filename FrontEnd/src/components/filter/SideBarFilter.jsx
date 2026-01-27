import { useEffect, useState } from "react";
import { X, Minus, Plus, ChevronRight } from "lucide-react";
import { useCategoryStore } from "../../stores/useCategoryStore";
import PriceFilter from "./PriceFilter";
import RateFilter from "./RateFilter";
import CategoryFilter from "./CategoryFilter";
import FilterSection from "./FilterSection";

export default function SideBarFilter() {
  const { loadCategories } = useCategoryStore();
  const [priceRange, setPriceRange] = useState({ min: "", max: "" });
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [selectedRating, setSelectedRating] = useState(null);

  useEffect(() => {
    loadCategories();
  }, []);

  const handleCategoryToggle = (catId) =>
    setSelectedCategories((prev) =>
      prev.includes(catId)
        ? prev.filter((id) => id !== catId)
        : [...prev, catId]
    );

  const clearAllFilters = () => {
    setSelectedCategories([]);
    setSelectedRating(null);
    setPriceRange({ min: "", max: "" });
  };

  const hasActiveFilters =
    selectedCategories.length > 0 ||
    selectedRating ||
    priceRange.min ||
    priceRange.max;

  return (
    <div className="w-full pr-0 lg:pr-8 bg-white">
      <div className="flex items-center justify-between pb-6 border-b border-[#0a0d1a]">
        <h2 className="font-serif italic text-2xl text-[#0a0d1a]">
            Filters
        </h2>
        {hasActiveFilters && (
          <button
            onClick={clearAllFilters}
            className="text-[10px] font-bold uppercase tracking-widest text-red-500 hover:text-black flex items-center gap-1 border-b border-transparent hover:border-black transition-all"
          >
            <X size={12} />
            Clear
          </button>
        )}
      </div>

      <div className="flex flex-col">
        {/* 1. Category Section */}
        <FilterSection title="Danh mục">
           <div className="pl-1 ">
             <CategoryFilter 
                selectedCategories={selectedCategories} 
                handleCategoryToggle={handleCategoryToggle} 
             />
           </div>
        </FilterSection>

        {/* 2. Price Section */}
        <FilterSection title="Khoảng giá" defaultOpen>
            <PriceFilter priceRange={priceRange} setPriceRange={setPriceRange} />
        </FilterSection>

        {/* 3. Rating Section */}
        <FilterSection title="Đánh giá" defaultOpen={false}>
            <RateFilter selectedRating={selectedRating} setSelectedRating={setSelectedRating} />
        </FilterSection>
      </div>
      
      <div className="mt-10 bg-[#f8f8f8] p-6 text-center">
        <p className="text-[#D2B48C] text-xs font-bold uppercase tracking-widest mb-2">New Season</p>
        <p className="font-serif italic text-lg mb-4">Autumn Collection 2024</p>
        <button className="text-xs font-bold border-b border-black pb-1 hover:text-[#D2B48C] hover:border-[#D2B48C] transition-colors">
            View Lookbook
        </button>
      </div>

    </div>
  );
}