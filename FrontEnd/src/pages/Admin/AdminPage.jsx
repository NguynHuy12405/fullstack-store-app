import { Outlet } from "react-router";
import SideBar from "../../components/navbar/SideBar";

export default function AdminPage() {
  return (
    <div className="flex h-screen bg-gray-100 text-black">
      <SideBar />
      {/* Main Content */}
      <div className="flex-1 p-6 overflow-auto">
        <Outlet />
      </div>
    </div>
  )
}
