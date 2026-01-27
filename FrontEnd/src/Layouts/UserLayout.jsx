import { useEffect } from "react";
import { Navigate, Outlet, useLocation } from "react-router";
import { useAuthStore } from "../stores/useAuthStore";
import Footer from "../components/footer/Footer";
import Header from "../components/header/Header";

export default function UserLayout() {
  const user = useAuthStore((state) => state.user);
  const { pathname } = useLocation();

  useEffect(() => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: "instant",
    });
  }, [pathname]);

  if (!user) {
    return <Navigate to="/login" state={{ from: pathname }} replace />;
  }

  return (
    <div className="min-h-screen flex flex-col bg-white font-sans text-[#0a0d1a]">
      <Header />

      <main className="grow pt-[110px] md:pt-[154px] flex flex-col animate-fade-in relative">
        <Outlet />
      </main>

      <Footer />
    </div>
  );
}