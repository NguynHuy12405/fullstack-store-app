import { create } from "zustand";
import { orders as mockOrders } from "../mockData/data";
import { useAuthStore } from "./useAuthStore";

export const useOrderStore = create((set, get) => ({
  orders: [...mockOrders],
  

  // Lấy tất cả order (Admin)
  getAllOrders: () => get().orders,

  // Lấy order của user hiện tại
  getOrdersByCurrentUser: () => {
    const currentUser = useAuthStore.getState().user;
    if (!currentUser) return [];
    return get().orders.filter(o => o.userId === currentUser.id);
  },

  // Lấy order theo ID
  getOrderById: (id) => get().orders.find(o => o.id === id),

  // Thêm order mới gắn userId
  addOrder: (orderData) => {
    const { orders } = get();
    const currentUser = useAuthStore.getState().user;

    if (!currentUser) {
      return { success: false, message: "Guest không thể tạo đơn hàng!" };
    }

    const newOrder = {
      id: "#ORD-" + (Math.floor(Math.random() * 9000) + 1000),
      userId: currentUser.id,
      customer: currentUser.name,
      date: new Date().toISOString().slice(0, 10),
      status: "Đang xử lý",
      ...orderData,
    };

    set({ orders: [...orders, newOrder] });

    return { success: true, order: newOrder };
  },

  // Cập nhật order
  updateOrder: (id, updated) => {
    set(state => ({
      orders: state.orders.map(o => (o.id === id ? { ...o, ...updated } : o)),
    }));
  },

  // Xóa order
  deleteOrder: (id) => {
    set(state => ({
      orders: state.orders.filter(o => o.id !== id),
    }));
  },
}));
