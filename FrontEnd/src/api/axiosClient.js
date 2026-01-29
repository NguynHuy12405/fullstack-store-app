import axios from "axios";

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? "http://127.0.0.1:8000/api",
  timeout: 10000,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});

/* ===================== REQUEST ===================== */
axiosClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("access_token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => Promise.reject(error)
);

/* ===================== RESPONSE ===================== */
axiosClient.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (!error.response) {
      console.error("Network error");
      return Promise.reject({
        message: "Không thể kết nối server",
      });
    }

    const { status, data } = error.response;

    // 401 → logout sau này
    if (status === 401) {
      localStorage.removeItem("access_token");
    }

    // Chuẩn format error cho FE
    return Promise.reject({
      status,
      message: data?.message || "Có lỗi xảy ra",
      errors: data?.errors || null,
    });
  }
);

export default axiosClient;
