import axios from "axios";

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || "http://localhost:4000/api",
  timeout: 6000,
  headers: {
    "Content-Type": "application/json",
  },
});

// ---------------
// REQUEST
// ---------------
axiosClient.interceptors.request.use(
  (config) => {
    // Nếu bạn có token:
    // const token = localStorage.getItem("access_token");
    // if (token) {
    //   config.headers.Authorization = `Bearer ${token}`;
    // }

    return config;
  },
  (error) => Promise.reject(error)
);

// ---------------
// RESPONSE
// ---------------
axiosClient.interceptors.response.use(
  (response) => response.data,
  (error) => {
    console.error("API Error:", error);
    return Promise.reject(error?.response?.data || error.message);
  }
);

export default axiosClient;
