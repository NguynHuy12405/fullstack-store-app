import axiosClient from "./axiosClient";

const productApi = {
  getAll: () => axiosClient.get("/products?limit=100"),
  getById: (id) => axiosClient.get(`/products/${id}`),
  search: (keyword) => axiosClient.get(`/products/search?q=${keyword}`),
  add: (data) => axiosClient.post("/products/add", data),
  update: (id, data) => axiosClient.put(`/products/${id}`, data),
  remove: (id) => axiosClient.delete(`/products/${id}`),
  getCategories: () => axiosClient.get("/products/categories"),

  // ====== MỚI – CHUẨN PAGINATION ======
  getList: ({
    page = 1,
    limit = 10,
    category,
    keyword,
    sort,
  }) => {
    const params = {
      limit,
      skip: (page - 1) * limit,
    };

    let url = "/products";

    if (category) {
      url = `/products/category/${category}`;
    }

    if (keyword) {
      url = "/products/search";
      params.q = keyword;
    }

    if (sort) {
      params.sort = sort; // dùng cho API riêng sau này
    }

    return axiosClient.get(url, { params });
  },
};

export default productApi;
