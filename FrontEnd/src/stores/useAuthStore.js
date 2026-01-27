import { create } from "zustand";
import { users as mockUsers } from "../mockData/data";

export const useAuthStore = create((set, get) => ({
  token: localStorage.getItem("token") || null,
  user: JSON.parse(localStorage.getItem("user")) || null,
  users: [...mockUsers],

  // LOGIN
  login: (email, password) => {
    const { users } = get();
    const foundUser = users.find((u) => u.email === email && u.password === password);

    if (!foundUser) {
      return { success: false, message: "Email hoặc mật khẩu không đúng" };
    }

    const fakeToken = "FAKE_TOKEN_" + Date.now();

    localStorage.setItem("token", fakeToken);
    localStorage.setItem("user", JSON.stringify(foundUser));

    set({ token: fakeToken, user: foundUser });

    return { success: true, role: foundUser.role };
  },

  // REGISTER
  register: (name, email, password) => {
    const { users } = get();
    const exists = users.find((u) => u.email === email);

    if (exists) {
      return { success: false, message: "Email đã tồn tại!" };
    }

    const newUser = {
      id: users.length + 1,
      name,
      email,
      password,
      role: "User",
      status: "Active",
      avatar: null,
    };

    const fakeToken = "FAKE_TOKEN_" + Date.now();
    set({ 
      users: [...users, newUser],
      user: newUser,
      token: fakeToken
    });

    localStorage.setItem("token", fakeToken);
    localStorage.setItem("user", JSON.stringify(newUser));

    return { success: true };
  },

  // Cập nhật user
  updateUser: (updatedUser) => {
    const { users } = get();
    const newUsers = users.map(u => u.id === updatedUser.id ? updatedUser : u);

    set({ 
      users: newUsers,
      user: updatedUser
    });

    localStorage.setItem("user", JSON.stringify(updatedUser));
  },

  // Xóa user
  deleteUser: (userId) => {
    const { users, user, token } = get();
    const newUsers = users.filter(u => u.id !== userId);

    let updatedUser = user;
    let updatedToken = token;

    // Nếu xóa chính mình thì logout
    if (user && user.id === userId) {
      updatedUser = null;
      updatedToken = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    }

    set({ users: newUsers, user: updatedUser, token: updatedToken });
  },

  // Logout
  logout: () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    set({ token: null, user: null });
  },
}));
