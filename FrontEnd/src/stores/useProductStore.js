import { create } from "zustand";
import productApi from "../api/productApi";

export const useProductStore = create((set, get) => ({
  // ===== DATA =====
  products: [],
  categories: [],
  productDetail: null,

  // ===== NEW: API PAGINATION =====
  page: 1,
  limit: 12,
  total: 0,
  category: null,
  loading: false,

  // ===== FORM =====
  formData: {
    name: "",
    price: "",
    stock: "",
    category: "",
    status: "Còn hàng",
    description: "",
  },
  images: [],

  // ===== HELPERS =====
  getCategoryName: (slug) => {
    if (!slug) return "Chưa phân loại";
    const cat = get().categories.find(c => c.slug === slug);
    return cat ? cat.name : "Chưa phân loại";
  },

  // ===== LOAD PRODUCTS (API READY) =====
  loadProducts: async () => {
    try {
      set({ loading: true });

      const { page, limit, category } = get();

      const data = await productApi.getList({
        page,
        limit,
        category,
      });

      set({
        products: data.products.map(p => ({
          id: p.id,
          name: p.title,
          price: Number(p.price) || 0,
          stock: p.stock ?? 10,
          category: p.category ?? "",
          categoryId: p.category ?? "",
          status:
            p.stock === 0 ? "Hết hàng" :
            p.stock <= 5 ? "Sắp hết" : "Còn hàng",
          image: p.thumbnail,
          images: p.images,
          description: p.description,
          rating: Number(p.rating) || 0,
          discountPercentage: Number(p.discountPercentage) || 0,
          reviewCount: Array.isArray(p.reviews) ? p.reviews.length : 0,
          reviews: Array.isArray(p.reviews) ? p.reviews : [],
        })),
        total: data.total,
        loading: false,
      });
    } catch (err) {
      console.error(err);
      set({ loading: false });
    }
  },

  // ===== DETAIL =====
  loadProductDetail: async (id) => {
    const p = await productApi.getById(id);
    set({
      productDetail: {
        id: p.id,
        name: p.title,
        price: p.price,
        stock: p.stock ?? 10,
        quantity: p.quantity ?? 10,
        category: p.category,
        image: p.thumbnail,
        images: p.images,
        description: p.description,
        reviews: p.reviews,
      },
    });
  },

  addReview: (review) =>
    set((state) => ({
      productDetail: {
        ...state.productDetail,
        reviews: [...(state.productDetail?.reviews || []), review],
      },
  })),


  // ===== PAGINATION =====
  setPage: (page) => set(() => ({ page: Math.max(1, page) })),
  setLimit: (limit) => set({ limit, page: 1 }),
  resetPagination: () => set({ page: 1, total: 0 }),
  setTotal: (total) => set({ total }),


  // ===== FILTER =====
  setCategory: (category) => set({ category, page: 1 }),

  // ===== FORM =====
  setFormData: (data) =>
    set({ formData: { ...get().formData, ...data } }),

  updateFormField: (field, value) =>
    set({ formData: { ...get().formData, [field]: value } }),

  resetForm: () =>
    set({
      formData: {
        name: "",
        price: "",
        stock: "",
        categoryId: "",
        status: "active",
        description: "",
      },
      images: [],
    }),

  // ===== IMAGES =====
  setImages: (imgs) => set({ images: imgs }),
  addImage: (img) =>
    set((state) => ({ images: [...state.images, img] })),
  removeImage: (index) => {
    const imgs = [...get().images];
    imgs.splice(index, 1);
    set({ images: imgs });
  },
}));

