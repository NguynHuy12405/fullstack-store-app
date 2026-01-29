import { create } from "zustand";
import productApi from "../api/productApi";

export const useProductStore = create((set, get) => ({
  // ===== DATA =====
  products: [],
  productDetail: null,

  // ===== PAGINATION (Laravel) =====
  page: 1,
  lastPage: 1,
  total: 0,
  categorySlug: null,
  loading: false,

  // ================= LOAD PRODUCTS =================
  loadProducts: async () => {
    try {
      set({ loading: true });

      const { page, categorySlug } = get();

      let res;
      if (categorySlug) {
        res = await productApi.getByCategory(categorySlug, { page });
      } else {
        res = await productApi.getAll({ page });
      }

      set({
        products: res.data,
        page: res.current_page,
        lastPage: res.last_page,
        total: res.total,
        loading: false,
      });
    } catch (err) {
      console.error("loadProducts error", err);
      set({ loading: false });
    }
  },

  // ================= DETAIL =================
  loadProductDetail: async (slug) => {
    try {
      set({ loading: true });
      const res = await productApi.getBySlug(slug);
      set({
        productDetail: res.data,
        loading: false,
      });
    } catch (err) {
      console.error("loadProductDetail error", err);
      set({ loading: false });
    }
  },

  // ================= PAGINATION =================
  setPage: (page) =>
    set(() => ({
      page: Math.max(1, page),
    })),

  resetPagination: () =>
    set({
      page: 1,
      lastPage: 1,
      total: 0,
    }),

  // ================= FILTER =================
  setCategory: (slug) =>
    set({
      categorySlug: slug,
      page: 1,
    }),
}));
