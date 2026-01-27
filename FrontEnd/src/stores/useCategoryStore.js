import { create } from "zustand";
// import categoryApi from "../api/categoryApi";
import { categories } from "../mockData/data";

export const useCategoryStore = create((set) => ({
  categories: [],
  loading: false,
  error: null,

  loadCategories: async () => {
    set({ loading: true });

    setTimeout(() => {
      set({
        categories: categories,
        loading: false,
      });
    }, 500);
  },

  // // Load danh sách category
  // loadCategory: async () => {
  //   set({ loading: true, error: null });

  //   try {
  //     const response = await categoryApi.getAll();

  //     set({
  //       categories: response.data || response,
  //       loading: false,
  //     });
  //   } catch (error) {
  //     console.error("Load category failed:", error);

  //     set({
  //       loading: false,
  //       error: error.message || "Không thể tải danh mục",
  //     });
  //   }
  // },


  // Reset store (tuỳ chọn)
  resetCategory: () => {
    set({
      categories: [],
      loading: false,
      error: null,
    });
  },
}));
