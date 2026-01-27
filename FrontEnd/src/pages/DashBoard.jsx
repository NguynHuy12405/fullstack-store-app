import { ArrowRight, Truck, ShieldCheck, RefreshCw, Clock } from "lucide-react";
import { Link } from "react-router-dom";
import ProductList from "../components/list/ProductList";
import Banner from "../components/banner/Banner";
import SectionHeader from "../components/SectionHeader";
import ButtonLink from "../components/Button/ButtonLink";

export default function DashBoard() {
  
  return (
    <div className="bg-white min-h-screen font-sans text-[#0a0d1a]">
      <Banner />

      <section className="py-16 md:py-24 container mx-auto">
        <SectionHeader title="New Arrivals" subtitle="Just In" linkTo="/products/newArrivals" />
        <div className="px-4">
            <ProductList titleProducts={""} /> 
        </div>
      </section>

      <section className="relative h-[500px] w-full bg-fixed bg-cover bg-center mb-16 md:mb-24 flex items-center justify-center"
        style={{ backgroundImage: "url('./img/banner2.jpg')" }}
      >
        <div className="absolute inset-0 bg-[#0a0d1a]/30"></div>
        <div className="relative z-10 text-center text-white px-4">
            <p className="text-[#D2B48C] text-xs font-bold uppercase tracking-[0.3em] mb-4">Limited Edition</p>
            <h2 className="text-5xl md:text-6xl font-serif italic mb-6">Street Vibes</h2>
            <p className="max-w-md mx-auto mb-8 font-light text-gray-200">
              Đánh thức bản lĩnh đường phố với những thiết kế táo bạo, phá vỡ mọi quy chuẩn để bạn tự do thể hiện chất riêng.
            </p>
            <ButtonLink title={"Shop The Look"} to={"/products"} />
        </div>
      </section>

      <section className="py-10 container mx-auto">
        <SectionHeader title="Best Sellers" subtitle="Customer Favorites" linkTo="/products/bestSellers" />
        <div className="px-4">
            <ProductList />
        </div>
      </section>

      <section className="py-16 md:py-24 bg-[#faf9f7] mt-16">
        <div className="container mx-auto px-4">
             <div className="text-center mb-16">
                <h2 className="text-3xl font-serif italic mb-4">Curated For You</h2>
                <div className="w-12 h-0.5 bg-[#0a0d1a] mx-auto"></div>
             </div>

             <div className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div className="order-2 md:order-1 space-y-6 pr-0 md:pr-12">
                    <span className="text-[#D2B48C] text-xs font-bold uppercase tracking-widest">Editor's Pick</span>
                    <h3 className="text-4xl font-serif">Urban Legends</h3>
                    <p className="text-gray-500 font-light leading-relaxed">
                        Tuyên ngôn của văn hóa đường phố.
                        Không chỉ là một đôi giày, đây là điểm nhấn hoàn hảo để 'nâng cấp' outfit của bạn với vẻ ngoài phóng khoáng và chất liệu cao cấp.
                    </p>
                    <Link to="/products" className="inline-flex items-center gap-2 text-sm font-bold border-b border-[#0a0d1a] pb-1 hover:text-[#D2B48C] hover:border-[#D2B48C] transition-all">
                        Khám phá ngay <ArrowRight size={16}/>
                    </Link>
                </div>
                <div className="order-1 md:order-2 h-[500px] overflow-hidden group">
                    <img 
                        src="./img/banner3.jpg" 
                        alt="Featured" 
                        className="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                </div>
             </div>
        </div>
      </section>

      <section className="py-16 container mx-auto mb-16">
         <div className="text-center mb-12">
            <h2 className="text-xl uppercase tracking-[0.2em] font-bold text-gray-300">More to Explore</h2>
         </div>
         <div className="px-4">
            <ProductList />
         </div>
         <div className="mt-12 text-center">
            <Link to="/products/collections" className="inline-block border border-[#0a0d1a] px-10 py-4 uppercase text-xs font-bold tracking-[0.2em] hover:bg-[#0a0d1a] hover:text-white transition-all">
                View Full Collection
            </Link>
         </div>
      </section>

    </div>
  );
}
