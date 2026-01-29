import axiosClient from "./axiosClient";

const categoryApi = {
  
  getCateBySlug: (slug) => axiosClient.get(`/products/categories/${slug}`),

};

export default categoryApi;
