import { useState } from "react";
import { Link, useNavigate } from "react-router";
import { useAuthStore } from "../../stores/useAuthStore";

export default function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const register = useAuthStore((state) => state.register);

  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    const res = register(name, email, password);

    if (!res.success) {
      alert(res.message);
      return;
    }

    alert("Đăng ký thành công!");
    navigate("/user");
  };

  return (
    <div className="min-h-screen w-full flex justify-center items-center bg-[#D2B48C] text-[#0a0d1a]">
      <div className="w-[450px] p-8 border rounded-2xl shadow-2xl bg-[#E7D7BD]">
        <h2 className="text-3xl font-bold text-center mb-8">Register</h2>

        <form onSubmit={handleSubmit} className="flex flex-col gap-5">
          <div className="flex flex-col">
            <label Name className="mb-1">Họ và Tên</label>
            <input
              type="text"
              placeholder="Họ và Tên..."
              className="border border-[#0a0d1a] p-3 "
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
          </div>

          <div className="flex flex-col">
            <label Email className="mb-1">Email</label>
            <input
              type="email"
              placeholder="Email..."
              className="border border-[#0a0d1a] p-3 "
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div className="flex flex-col">
            <label Password className="mb-1">Mật Khẩu</label>
            <input
              type="password"
              placeholder="Mật Khẩu..."
              className="border border-[#0a0d1a] p-3 "
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          <div className="flex flex-col">
            <label Password className="mb-1">Xác Nhận Mật Khẩu</label>
            <input
              type="password"
              placeholder="Xác Nhận Mật Khẩu..."
              className="border border-[#0a0d1a] p-3 "
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
            />
          </div>

          <div className="flex justify-between text-[16px]">
            <div className="flex justify-center items-center">
              <input type="checkbox" className="w-4 h-4 rounded border-gray-400 accent-orange-600 cursor-pointer hover:shadow-md transition-all" />
              <label Password className="ml-2">Remember Password</label>
            </div>
            <div>
              <Link to="" className="text-[#0a0d1a] text-[14px] hover:underline">Forgot Password</Link>
            </div>
          </div>

          <button
            type="submit"
            className="bg-[#0a0d1a] hover:bg-[#D2B48C] hover:text-[#0a0d1a] text-[#ffffff] transition duration-200 py-3 font-semibold cursor-pointer"
          >
            Register
          </button>
          <p className="mt-4 mr-4 text-center text-sm">
            Đã có tài khoản?{" "}
            <Link to="/login" className="text-[#0a0d1a] hover:underline">
              Đăng nhập ngay
            </Link>
          </p>

        </form>
      </div>
    </div>
  );
}
