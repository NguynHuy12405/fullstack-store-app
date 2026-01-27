import { create } from "zustand";
import { usePaginationStore } from "./usePaginationStore";

export const useFilterStore = create((set) => ({
  category: null,
  priceMin: 0,
  priceMax: 5000,
  rating: null,

  setCategory: (cat) => {
    usePaginationStore.getState().resetPage();
    set({ category: cat });
  },

  setPrice: (min, max) => {
    usePaginationStore.getState().resetPage();
    set({ priceMin: min, priceMax: max });
  },

  resetFilters: () => {
    usePaginationStore.getState().resetPage();
    set({
      category: null,
      priceMin: 0,
      priceMax: 5000,
      rating: null,
    });
  },
}));
