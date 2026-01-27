import { getRevenueStats } from "../utils/GetTotalRevenue";

// Orders
export const orders = [
  {
    id: "#ORD-7782",
    userId: 1,
    customer: "Nguyễn Văn A",
    date: "2023-10-25", 
    items: [
      {
        id: 1,
        nameItem: "Áo Thun Xanh",
        quantity: 2,
      },
      {
        id: 2,
        nameItem: "Áo Thun Đỏ",
        quantity: 1,
      },
    ],
    total: 32500000,
    status: "Hoàn thành",
  },
  { 
    id: "#ORD-7783",
    userId: 3,
    customer: "Trần Thị B",
    date: "2023-10-26", 
    items: [
      {
        id: 1,
        nameItem: "Áo Thun Xanh",
        quantity: 2,
      },
      {
        id: 2,
        nameItem: "Áo Thun Đỏ",
        quantity: 1,
      },
    ], total: 1200000,
    status: "Đang xử lý"
  },
  { 
    id: "#ORD-7784",
    userId: 4,
    customer: "Lê Hoàng C",
    date: "2023-10-26",
    items: [
      {
        id: 1,
        nameItem: "Áo Thun Trắng",
        quantity: 2,
      },
      {
        id: 2,
        nameItem: "Áo Thun Vàng",
        quantity: 1,
      },
    ],
    total: 15800000,
    status: "Đang giao"
  },
  {
    id: "#ORD-7785",
    customer: "Phạm Nhật D",
    date: "2023-10-27",
    items: [
      {
        id: 1,
        nameItem: "Áo Thun Tím",
        quantity: 2,
      },
      {
        id: 2,
        nameItem: "Áo Thun Hồng",
        quantity: 1,
      },
    ],
    total: 550000,
    status: "Đã hủy"
  },
];

export const categories = [
  {
    id: 1,
    slug: "adidas",
    name: "Adidas",
  },
  {
    id: 2,
    slug: "nike",
    name: "Nike",
  },
  {
    id: 3,
    slug: "puma",
    name: "Puma",
  },
  {
    id: 4,
    slug: "balenciaga",
    name: "Balenciaga",
  },
  {
    id: 5,
    slug: "vans",
    name: "Vans",
  },
  {
    id: 6,
    slug: "mlb",
    name: "MLB",
  },
];

// Users
export const users = [
  { id: 1, name: "Admin", email: "admin@gmail.com", password: "1", role: "Admin", status: "Active", avatar: "https://i.pravatar.cc/150?u=1" },
  { id: 2, name: "Trần Thị B", email: "tranthib@example.com", password: "0", role: "User", status: "Active", avatar: "https://i.pravatar.cc/150?u=2" },
  { id: 3, name: "Lê Văn C", email: "levanc@example.com", password: "2", role: "Admin", status: "Inactive", avatar: "https://i.pravatar.cc/150?u=3" },
  { id: 4, name: "Phạm Thị D", email: "phamthid@example.com", password: "0", role: "User", status: "Active", avatar: "https://i.pravatar.cc/150?u=4" },
  { id: 5, name: "Hoàng Văn E", email: "hoangvane@example.com", password: "0", role: "User", status: "Suspended", avatar: "https://i.pravatar.cc/150?u=5" },
  { id: 6, name: "Nguyễn Văn A", email: "nguyenvana@example.com", password: "1", role: "Admin", status: "Active", avatar: "https://i.pravatar.cc/150?u=6" },
];

// SliderShows
export const slider = [
  {
    id: 1,
    type: "video", // Đánh dấu đây là video
    src: "/video/high.mp4",
    title: "Street Style",
    subtitle: "Most Popular",
    desc: "Đánh thức bản lĩnh đường phố với những thiết kế táo bạo."
  },
  {
    id: 2,
    type: "image", // Đánh dấu đây là ảnh
    src: "./img/banner4.jpg",
    title: "Hiphop & Drill",
    subtitle: "New Collection",
    desc: "Khám phá vẻ đẹp thanh lịch của mùa thu với những thiết kế mới nhất."
  },
  {
    id: 3,
    type: "image",
    src: "./img/banner1.jpg",
    title: "Gangster",
    subtitle: "Best Seller",
    desc: "Sự tối giản làm nên đẳng cấp khác biệt."
  },
];

// Stats
const revenueStats = getRevenueStats();

export const stats = [
  {
    label: "Doanh thu tháng",
    value: revenueStats.current.toLocaleString("vi-VN") + "₫",
    percent: revenueStats.percent,
  },
  {
    label: "Đơn hàng",
    value: orders.length,
    percent: 3.5,
  },
  {
    label: "Khách hàng",
    value: users.length,
    percent: 1.2,
  }
];

export const option_Price = [
  { label: "Dưới 100k", max: 100000 },
  { label: "Dưới 500k", max: 500000 },
  { label: "500k - 1 triệu", min: 500000, max: 1000000 },
  { label: "Trên 1 triệu", min: 1000000 }
];