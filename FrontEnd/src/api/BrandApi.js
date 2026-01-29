import axiosClient from "./axiosClient";

const brandApi = {
  getBrandBySlug: (slug) => axiosClient.get(`/products/brand/${slug}`),
};

export default brandApi;
