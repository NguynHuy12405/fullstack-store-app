// src/api/categoryApi.js
import axiosClient from "./axiosClient";

const categoryApi = {
  getAll: () => axiosClient.get("/products/categories"),

  // Demo giả backend (khi bạn có API thật chỉ cần thay URL)
  create: (data) => axiosClient.post("/categories", data),
  update: (id, data) => axiosClient.put(`/categories/${id}`, data),
  delete: (id) => axiosClient.delete(`/categories/${id}`),
};

export default categoryApi;
