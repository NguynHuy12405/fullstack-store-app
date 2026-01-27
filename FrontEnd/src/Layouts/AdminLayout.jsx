import { Navigate, Outlet } from "react-router";
import { useAuthStore } from "../stores/useAuthStore";

export default function AdminLayout() {
  const user = useAuthStore(state => state.user);

  if (!user) return <Navigate to="/login" replace />;

  if (user.role !== "Admin") return <Navigate to="/forbidden-403" replace />;
  
  return <Outlet />;
}
