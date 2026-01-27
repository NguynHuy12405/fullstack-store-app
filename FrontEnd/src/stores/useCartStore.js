import { create } from 'zustand';

export const useCartStore = create((set, get) => ({
  cartItems: [],
  
  addToCart: (product, quantity = 1) => set((state) => {
    const existingItem = state.cartItems.find(item => item.id === product.id);
    if (existingItem) {
      return {
        cartItems: state.cartItems.map(item =>
          item.id === product.id
            ? { ...item, quantity: item.quantity + quantity }
            : item
        )
      };
    }
    return {
      cartItems: [...state.cartItems, { ...product, quantity }]
    };
  }),
  
  removeFromCart: (id) => set((state) => ({
    cartItems: state.cartItems.filter(item => item.id !== id)
  })),
  
  updateQuantity: (id, quantity) => set((state) => {
    if (quantity <= 0) {
      return {
        cartItems: state.cartItems.filter(item => item.id !== id)
      };
    }
    return {
      cartItems: state.cartItems.map(item =>
        item.id === id ? { ...item, quantity } : item
      )
    };
  }),
  
  clearCart: () => set({ cartItems: [] }),
  
  getTotalPrice: () => {
    const state = get();
    return state.cartItems.reduce(
      (total, item) => total + (Number(item.price) * item.quantity),
      0
    );
  },
  
  getTotalItems: () => {
    const state = get();
    return state.cartItems.reduce((total, item) => total + item.quantity, 0);
  },
}));
