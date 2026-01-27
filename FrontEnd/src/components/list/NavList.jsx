import { ChevronDown } from "lucide-react";
import { Link } from "react-router-dom";
import { useCategoryStore } from "../../stores/useCategoryStore";
import { useEffect } from "react";

export default function NavList() {
    const { categories, loadCategories } = useCategoryStore();

    useEffect(() => {
        loadCategories();
    }, []);

  return (
    <ul className="flex items-center gap-8 h-full">
      {categories.map((category) => (
        <li
          key={category.id}
          className="group h-full flex items-center relative"
        >
            <Link
                to={`/products/category?group=${category.slug}`}
                className="flex items-center gap-1 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#0a0d1a] hover:text-[#D2B48C] transition-colors relative"
            >
                {category.name}
                <ChevronDown size={10} className="opacity-40 group-hover:text-[#D2B48C]" />
            </Link>
        </li>
      ))}
    </ul>
  )
}
