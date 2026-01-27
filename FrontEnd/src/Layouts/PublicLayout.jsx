import { useEffect } from "react";
import { Outlet, useLocation } from "react-router";
import Footer from "../components/footer/Footer";
import Header from "../components/header/Header";

export default function PublicLayout() {
  const { pathname } = useLocation();

  useEffect(() => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: "instant",
    });
  }, [pathname]);

  return (
    <div className="min-h-screen flex flex-col bg-white font-sans text-[#0a0d1a]">
      <Header />

      <main className="grow pt-[110px] md:pt-[154px] flex flex-col animate-fade-in">
        <Outlet />
      </main>
      
      <Footer />
    </div>
  );
}