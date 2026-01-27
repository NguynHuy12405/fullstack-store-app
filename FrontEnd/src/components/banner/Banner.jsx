import { ArrowRight, ArrowLeft , Truck, ShieldCheck, RefreshCw, Clock } from "lucide-react";
import { useEffect, useState } from "react";
import { slider } from "../../mockData/data";
import { useNavigate } from "react-router";
import ServiceItem from "../item/ServiceItem";

export default function Banner() {
  const [current, setCurrent] = useState(0);
  const [pause, setPause] = useState(false);
  const navigate = useNavigate();

  const length = slider.length;

  const nextSlide = () => {
    setCurrent(current === length - 1 ? 0 : current + 1);
  };

  const prevSlide = () => {
    setCurrent(current === 0 ? length - 1 : current - 1);
  };

  useEffect(() => {
    if (pause) return;
    const timer = setInterval(nextSlide, 5000);
    return () => clearInterval(timer);
  }, [current, pause]);

  if (!Array.isArray(slider) || slider.length <= 0) {
    return null;
  }

  return (
    <>
      <div
        className="relative w-full h-[600px] md:h-[85vh] overflow-hidden bg-[#0a0d1a]"
        onMouseEnter={() => setPause(true)}
        onMouseLeave={() => setPause(false)}
      >
        {slider.map((slide, index) => (
          <div
            key={index}
            className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${
              index === current ? "opacity-100 z-10" : "opacity-0 z-0"
            }`}
          >
            <div className="absolute inset-0 overflow-hidden">
              {slide.type === 'video' ? (
                <video
                  className="w-full h-full object-cover"
                  src={slide.src}
                  autoPlay
                  loop
                  muted
                  playsInline
                />
              ) : (
                <img
                  className="w-full h-full object-cover"
                  src={slide.src}
                  alt={slide.title}
                />
              )}
              <div className="absolute inset-0 bg-black/30 md:bg-black/20"></div>
            </div>

            <div className="absolute inset-0 flex flex-col items-center justify-center text-center px-4 max-w-4xl mx-auto">
              <div className={`transition-all duration-700 delay-300 transform ${
                  index === current ? "translate-y-0 opacity-100" : "translate-y-8 opacity-0"
              }`}>
                  <span className="text-[#D2B48C] text-xs md:text-sm font-bold uppercase tracking-[0.3em] mb-4 block">
                      {slide.subtitle || "New Collection"}
                  </span>
              </div>

              <h1 className={`text-5xl md:text-7xl lg:text-8xl font-serif text-white font-medium leading-tight mb-6 transition-all duration-700 delay-500 transform ${
                  index === current ? "translate-y-0 opacity-100" : "translate-y-8 opacity-0"
              }`}>
                {slide.title}
              </h1>

              {slide.desc && (
                  <p className={`text-gray-200 text-sm md:text-base font-light max-w-lg mb-8 transition-all duration-700 delay-700 transform ${
                      index === current ? "translate-y-0 opacity-100" : "translate-y-8 opacity-0"
                  }`}>
                      {slide.desc}
                  </p>
              )}

              <div className={`transition-all duration-700 delay-700 transform ${
                  index === current ? "translate-y-0 opacity-100" : "translate-y-8 opacity-0"
              }`}>
                  <button
                      onClick={() => navigate("/products")}
                      className="group relative px-8 py-4 bg-transparent overflow-hidden"
                  >
                      <span className="absolute inset-0 border border-white group-hover:bg-[#D2B48C] transition-all duration-300"></span>
                      
                      <span className="relative flex items-center gap-3 text-white group-hover:text-[#0a0d1a] uppercase text-xs font-bold tracking-[0.2em] transition-colors duration-300">
                          Mua Ngay <ArrowRight size={16} />
                      </span>
                  </button>
              </div>
            </div>
          </div>
        ))}

        <button
          onClick={prevSlide}
          className="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 text-white/50 hover:text-white transition-colors hidden md:block"
        >
          <ArrowLeft size={40} strokeWidth={1} />
        </button>

        <button
          onClick={nextSlide}
          className="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 text-white/50 hover:text-white transition-colors hidden md:block"
        >
          <ArrowRight size={40} strokeWidth={1} />
        </button>

        <div className="absolute bottom-8 left-0 w-full flex items-center justify-center z-20 gap-4">
          <span className="text-white text-xs font-bold tracking-widest">
              0{current + 1}
          </span>
          
          <div className="flex gap-2">
              {slider.map((_, i) => (
                  <div
                      key={i}
                      onClick={() => setCurrent(i)}
                      className={`h-0.5 cursor-pointer transition-all duration-500 ${
                          i === current ? "w-12 bg-[#D2B48C]" : "w-6 bg-white/30 hover:bg-white"
                      }`}
                  ></div>
              ))}
          </div>

          <span className="text-white/50 text-xs font-bold tracking-widest">
              0{length}
          </span>
        </div>
      </div>
      
      <div className="bg-[#faf9f7] py-8 border-b border-gray-100">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <ServiceItem icon={<Truck />} title="Free Shipping" desc="On orders over 500k" />
            <ServiceItem icon={<RefreshCw />} title="Free Returns" desc="Within 30 days" />
            <ServiceItem icon={<ShieldCheck />} title="Secure Payment" desc="100% Protected" />
            <ServiceItem icon={<Clock />} title="Support 24/7" desc="Dedicated Support" />
          </div>
        </div>
      </div>
    </>
  );
}