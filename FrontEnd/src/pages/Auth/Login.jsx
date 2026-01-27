import { useState } from "react";
import { Link, useNavigate } from "react-router";
import { useAuthStore } from "../../stores/useAuthStore";

export default function Login() {
  const login = useAuthStore((state) => state.login);
  const [password, setPassword] = useState("");
  const [email, setEmail] = useState("");
  const navigate = useNavigate();


  const handleSubmit = () => {
    const res = login(email, password);

    if (!res.success) return alert(res.message);

    if (res.role === "admin") navigate("/admin");
    else navigate("/user");
  };

  return (
    <div className="min-h-screen w-full flex justify-center items-center bg-[#D2B48C] text-[#0a0d1a]">
      <div className="w-[450px] p-8 border rounded-2xl shadow-2xl bg-[#E7D7BD]">
        <h2 className="text-3xl font-bold text-center mb-8">Login</h2>

        <form onSubmit={handleSubmit} className="flex flex-col gap-6">
          <div className="flex flex-col">
            <label Email className="mb-1">Email</label>
            <input
              type="email"
              placeholder="Email..."
              className="border border-[#0a0d1a] p-3"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div className="flex flex-col">
            <label Password className="mb-1">Password</label>
            <input
              type="password"
              placeholder="Password..."
              className="border border-[#0a0d1a] p-3"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          <div className="flex justify-between text-[16px]">
            <div className="flex justify-center items-center">
              <input type="checkbox" className="w-3 h-3 rounded border accent-[#0a0d1a] cursor-pointer hover:shadow-md transition-all" />
              <label Password className="ml-2 text-[14px]">Remember Password</label>
            </div>
            <div>
              <Link to="" className="text-[#0a0d1a] text-[14px] hover:underline">Forgot Password</Link>
            </div>
          </div>

          <button
            type="submit"
            className="bg-[#0a0d1a] hover:bg-[#D2B48C] hover:text-[#0a0d1a] text-[#ffffff] transition duration-200 py-3 font-semibold cursor-pointer"
          >
            Login
          </button>
          <p className="mt-2 mr-2 text-center text-sm">
            Đã chưa có tài khoản?{" "}
            <Link to="/register" className="text-[#0a0d1a] hover:underline">
              Đăng ký ngay
            </Link>
          </p>

          <div className="text-center">
            <p className="text-xs font-medium tracking-wide">More</p>
            <div className="flex justify-center items-center gap-4 mt-3">
              {/* GitHub */}
              <Link
                to=""
                className="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-200 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 432 416"><path fill="#000000" d="M213.5 0q88.5 0 151 62.5T427 213q0 70-41 125.5T281 416q-14 2-14-11v-58q0-27-15-40q44-5 70.5-27t26.5-77q0-34-22-58q11-26-2-57q-18-5-58 22q-26-7-54-7t-53 7q-18-12-32.5-17.5T107 88h-6q-12 31-2 57q-22 24-22 58q0 55 27 77t70 27q-11 10-13 29q-42 18-62-18q-12-20-33-22q-2 0-4.5.5t-5 3.5t8.5 9q14 7 23 31q1 2 2 4.5t6.5 9.5t13 10.5T130 371t30-2v36q0 13-14 11q-64-22-105-77.5T0 213q0-88 62.5-150.5T213.5 0z"/></svg>
              </Link>

              {/* Facebook */}
              <Link
                to=""
                className="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-200 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256"><path fill="#1877F2" d="M256 128C256 57.308 198.692 0 128 0C57.308 0 0 57.307 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.347-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.958 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/><path fill="#FFF" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A128.959 128.959 0 0 0 128 256a128.9 128.9 0 0 0 20-1.555V165h29.825"/></svg>
              </Link>

              {/* Google */}
              <Link
                to=""
                className="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-200 transition"
              >
                <svg 
                  xmlns="http://www.w3.org/2000/svg" 
                  width="32" height="32" 
                  viewBox="0 0 48 48"
                >
                  <path fill="#FFC107" d="M43.6 20.4H42V20H24v8h11.3C33.7 33.5 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34.3 5.2 29.4 3 24 3 12.4 3 3 12.4 3 24s9.4 21 21 21c10.5 0 20-7.7 20-21 0-1.3-.1-2.3-.4-3.6z"/>
                  <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 14.1 19 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34.3 5.2 29.4 3 24 3 15.7 3 8.6 7.8 6.3 14.7z"/>
                  <path fill="#4CAF50" d="M24 45c5.2 0 10-2 13.6-5.4l-6.3-5.3C29.4 36.9 26.8 38 24 38c-5.2 0-9.6-3.5-11.2-8.3l-6.6 5.1C8.6 40.2 15.7 45 24 45z"/>
                  <path fill="#1976D2" d="M43.6 20.4H42V20H24v8h11.3c-.8 2.2-2.3 4.1-4.4 5.4l6.3 5.3C40 35.3 43 30.1 43.6 20.4z"/>
                </svg>
              </Link>
            </div>
          </div>
        </form>

      </div>
    </div>
  );
}
