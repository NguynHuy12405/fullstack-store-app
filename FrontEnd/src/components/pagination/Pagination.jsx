export default function Pagination({ totalPages, currentPage, onPageChange }) {
  if (totalPages <= 1) return null;

  const getPageList = () => {
    let pages = [];

    if (totalPages <= 7) {
      for (let i = 1; i <= totalPages; i++) pages.push(i);
    } else {
      pages.push(1);

      if (currentPage > 3) pages.push("...");

      const start = Math.max(2, currentPage - 1);
      const end = Math.min(totalPages - 1, currentPage + 1);

      for (let i = start; i <= end; i++) pages.push(i);

      if (currentPage < totalPages - 2) pages.push("...");

      pages.push(totalPages);
    }

    return pages;
  };

  const pageList = getPageList();

  return (
    <div className="flex justify-center mt-10 gap-2">
      {/* Previous */}
      <button
        disabled={currentPage === 1}
        onClick={() => onPageChange(currentPage - 1)}
        className={`px-4 py-2 rounded-xl text-sm border border-black/20 transition cursor-pointer
          ${currentPage === 1
            ? "opacity-30 cursor-not-allowed"
            : "hover:bg-[#D2B48C]/20"
          }`}
      >
        Trước
      </button>

      {/* Pages */}
      {pageList.map((page, index) =>
        page === "..." ? (
          <span
            key={`dots-${index}`}
            className="px-3 py-2 text-black/40 select-none"
          >
            ...
          </span>
        ) : (
          <button
            key={`page-${page}`}
            onClick={() => onPageChange(page)}
            className={`w-10 h-10 rounded-xl border flex items-center justify-center transition cursor-pointer
              ${currentPage === page
                ? "bg-black text-[#D2B48C] border-black shadow scale-105"
                : "bg-white border-black/20 hover:bg-[#D2B48C]/20"
              }`}
          >
            {page}
          </button>
        )
      )}

      {/* Next */}
      <button
        disabled={currentPage === totalPages}
        onClick={() => onPageChange(currentPage + 1)}
        className={`px-4 py-2 rounded-xl text-sm border border-black/20 transition cursor-pointer
          ${currentPage === totalPages
            ? "opacity-30 cursor-not-allowed"
            : "hover:bg-[#D2B48C]/20"
          }`}
      >
        Sau
      </button>
    </div>
  );
}
