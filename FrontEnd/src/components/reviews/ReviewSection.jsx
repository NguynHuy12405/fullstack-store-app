import ReviewCard from "./ReviewCard";

export default function ReviewSection({ reviews }) {
  if (!reviews || reviews.length === 0) return null;

  return (
    <section className="py-20 bg-white border-t border-gray-100 font-sans text-[#0a0d1a]">
      <div className="max-w-7xl mx-auto px-4 md:px-8">
        
        {/* Header Section */}
        <div className="flex flex-col items-center mb-16 text-center">
          <span className="text-[#D2B48C] text-xs font-bold uppercase tracking-[0.3em] mb-4">
            Testimonials
          </span>
          <h2 className="text-4xl md:text-5xl font-serif italic mb-6">
            Client Diaries
          </h2>
          <div className="w-12 h-0.5 bg-[#0a0d1a]"></div>
        </div>

        {/* Reviews Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
          {reviews.map((review) => (
            <ReviewCard key={review.id} review={review} />
          ))}
        </div>

      </div>
    </section>
  );
}