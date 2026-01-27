import { useCategoryStore } from "../../stores/useCategoryStore";

export default function CategoryFilter({
  selectedCategories = [],
  handleCategoryToggle
}) {
  const { categories } = useCategoryStore();

  if (!categories || !Object.keys(categories).length) return null;

  return (
    <div>
      <h3 className="font-semibold text-gray-800 mb-3 flex items-center gap-2">
        <span>Danh mục</span>
        <span className="text-xs text-gray-400">
          ({selectedCategories.length})
        </span>
      </h3>

      <div className="space-y-4 overflow-y-scroll">
        {Object.entries(categories).map(([groupName, items]) => {
          if (!items?.length) return null;

          const selectedInGroup = items.filter(cat =>
            selectedCategories.includes(cat.id)
          ).length;

          return (
            <div
              key={groupName}
              className="border border-gray-200 rounded-lg p-3"
            >
              <div className="flex items-center gap-2 mb-2">
                <span className="text-sm font-medium text-gray-800">
                  {groupName}
                </span>
                {selectedInGroup > 0 && (
                  <span className="text-xs bg-[#D2B48C]/20 text-black px-2 py-0.5 rounded-full">
                    {selectedInGroup}
                  </span>
                )}
              </div>
              <div className="space-y-2">
                {items.map(cat => (
                  <label
                    key={cat.id}
                    className="flex items-center gap-2 cursor-pointer group py-1"
                  >
                    <input
                      type="checkbox"
                      checked={selectedCategories.includes(cat.id)}
                      onChange={() => handleCategoryToggle(cat.id)}
                      className="peer hidden"
                    />
                    <div
                      className="w-5 h-5 rounded border border-gray-500 bg-gray-200
                                 peer-checked:bg-[#D2B48C] peer-checked:border-black transition"
                    />
                    <span className="text-sm text-gray-700 group-hover:text-black transition-colors">
                      {cat.name}
                    </span>
                  </label>
                ))}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
