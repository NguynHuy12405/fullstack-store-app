import { BrowserRouter, Route, Routes } from "react-router";
import DashBoard from "./pages/DashBoard";
import GuestLayout from "./Layouts/GuestLayout";
import UserLayout from "./Layouts/UserLayout";
import AdminLayout from "./Layouts/AdminLayout";
import Forbidden403 from "./pages/Error/Forbidden403";
import NotFound404 from "./pages/Error/NotFound404";
import Login from "./pages/Auth/Login";
import Register from "./pages/Auth/Register";
import Profile from "./pages/User/Profile";
import Cart from "./pages/Carts/Cart";
import UserManager from "./pages/Admin/Pages/UserManage";
import AdminPage from "./pages/Admin/AdminPage";
import DashboardManager from "./pages/Admin/Pages/AdminDashboard";
import Checkout from "./pages/Checkouts/Checkout";
import ProductPage from "./pages/Products/ProductPage";
import PublicLayout from "./Layouts/PublicLayout";
import ManageProducts from "./pages/Admin/Pages/ProductsManage";
import ManageOrders from "./pages/Admin/Pages/OrdersManage";
import ProductDetail from "./pages/Products/ProductDetail";
import OrdersPage from "./pages/Order/OrdersPage";
import ContactUs from "./pages/ContactUs";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/*  Guest  */}
        <Route element={<GuestLayout />}>
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
        </Route>

        {/* (Guest xem thoải mái) */}
        <Route element={<PublicLayout />}>
          <Route path="/" element={<DashBoard />} />
          <Route path="/contact-us" element={<ContactUs />} />
          <Route path="/products" element={<ProductPage />} />
          <Route path="/products/:id" element={<ProductDetail />} />
        </Route>

        {/*  User (user + admin) */}
        <Route element={<UserLayout />}>
          <Route path="/profile" element={<Profile />} />
          <Route path="/cart" element={<Cart />} />
          <Route path="/orders" element={<OrdersPage />} />
          <Route path="/checkout" element={<Checkout />} />
        </Route>

        {/*  Admin  */}
        <Route element={<AdminLayout />}>
          <Route path="/admin" element={<AdminPage />}>
            <Route index element={ <DashboardManager /> } />
            <Route path="/admin/users" element={<UserManager />} />
            <Route path="/admin/products" element={<ManageProducts />} />
            <Route path="/admin/orders" element={<ManageOrders />} />
          </Route>
        </Route>

        {/* Errors */}
        <Route path="/forbidden-403" element={ <Forbidden403 /> } />
        <Route path="*" element={ <NotFound404 /> } />

      </Routes>
    </BrowserRouter>
  );
}

export default App;
