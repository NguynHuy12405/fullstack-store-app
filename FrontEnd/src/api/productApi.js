import axiosClient from "./axiosClient";

const productApi = {
  // Danh sách
  getAll: (params) => axiosClient.get("/products", { params }),

  // Chi tiết
  getBySlug: (slug) => axiosClient.get(`/products/${slug}`),

  // Search
  search: (keyword, params = {}) =>
    axiosClient.get("/products/search", {
      params: { q: keyword, ...params },
    }),

  // Category
  getByCategory: (slug, params = {}) =>
    axiosClient.get(`/products/categories/${slug}`, { params }),

  // Brand
  getByBrand: (slug, params = {}) =>
    axiosClient.get(`/products/brands/${slug}`, { params }),
};

export default productApi;
