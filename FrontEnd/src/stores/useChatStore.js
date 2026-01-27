import { create } from 'zustand';

export const useChatStore = create((set) => ({
  isOpen: false,
  messages: [
    { 
      id: 'welcome', 
      role: 'assistant', 
      content: 'Xin chào! Tôi có thể giúp bạn tìm kiếm đồ công nghệ nào hôm nay?', 
      type: 'text' 
    }
  ],
  isLoading: false,

  toggleChat: () => set((state) => ({ isOpen: !state.isOpen })),
  
  addMessage: (message) => set((state) => ({ 
    messages: [...state.messages, { ...message, id: Date.now() }] 
  })),
  
  setLoading: (loading) => set({ isLoading: loading }),
}));