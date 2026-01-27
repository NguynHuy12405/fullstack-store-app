import { useState, useEffect } from "react";
import { X, UploadCloud, Save, Trash2 } from 'lucide-react';
import { useAuthStore } from "../../../stores/useAuthStore";

export default function EditUser({ isOpen, onClose, user }) {
  const updateUser = useAuthStore((state) => state.updateUser);
  const deleteUser = useAuthStore((state) => state.deleteUser);

  const [imagePreview, setImagePreview] = useState(null);
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("user");
  const [status, setStatus] = useState("Active");

  useEffect(() => {
    if (user) {
      setName(user.name || "");
      setEmail(user.email || "");
      setPassword(user.password || "");
      setRole(user.role || "user");
      setStatus(user.status || "Active");
      setImagePreview(user.avatar || null);
    }
  }, [user]);

  if (!isOpen || !user) return null;

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) setImagePreview(URL.createObjectURL(file));
  };

  const handleSubmitForm = (e) => {
    e.preventDefault();
    const updatedUser = {
      ...user,
      name,
      email,
      password,
      role,
      status,
      avatar: imagePreview,
    };
    updateUser(updatedUser); // update store
    onClose();
  };

  const handleDelete = () => {
    if (confirm("Bạn có chắc muốn xóa user này?")) {
      deleteUser(user.id); // xóa user
      onClose();
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
      <div className="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">
        {/* Header */}
        <div className="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
          <h2 className="text-xl font-bold text-gray-800">Edit User</h2>
          <button onClick={onClose} className="p-2 text-gray-400 hover:text-gray-600 rounded-full">
            <X size={20} />
          </button>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmitForm} className="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
          {/* Avatar */}
          <div className="space-y-4">
            <label className="block text-sm font-medium text-gray-700">Hình ảnh Avatar</label>
            <div className="relative group">
              <div className={`border-2 border-dashed rounded-xl h-64 flex flex-col items-center justify-center cursor-pointer transition-all ${imagePreview ? 'border-blue-500 bg-blue-50/30' : 'border-gray-300 hover:border-blue-400 hover:bg-gray-50'}`}>
                {imagePreview ? (
                  <div className="relative w-full h-full p-2">
                    <img src={imagePreview} alt="Preview" className="w-full h-full object-contain rounded-lg" />
                    <button 
                      type="button"
                      onClick={() => setImagePreview(null)}
                      className="absolute top-3 right-3 bg-white/80 p-1.5 rounded-full shadow-sm hover:text-red-500"
                    >
                      <X size={16}/>
                    </button>
                  </div>
                ) : (
                  <>
                    <div className="bg-blue-50 p-3 rounded-full mb-3 text-blue-600 group-hover:scale-110 transition-transform">
                      <UploadCloud size={24} />
                    </div>
                    <p className="text-sm font-medium text-gray-700">Nhấn để tải ảnh lên</p>
                    <p className="text-xs text-gray-400 mt-1">PNG, JPG (Tối đa 5MB)</p>
                  </>
                )}
                <input 
                  type="file" 
                  className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                  onChange={handleImageChange}
                  accept="image/*"
                />
              </div>
            </div>
          </div>

          {/* Thông tin */}
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Tên Người Dùng</label>
              <input 
                value={name}
                onChange={(e) => setName(e.target.value)}
                type="text" 
                placeholder="Nhập Tên Người Dùng..."
                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                type="email" 
                placeholder="Email..."
                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                type="password" 
                placeholder="Password..."
                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
              />
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select
                  value={role}
                  onChange={(e) => setRole(e.target.value)}
                  className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm bg-white"
                >
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                  <option value="editor">Editor</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select
                  value={status}
                  onChange={(e) => setStatus(e.target.value)}
                  className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm bg-white"
                >
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                  <option value="Suspended">Suspended</option>
                </select>
              </div>
            </div>
          </div>
        </form>

        <div className="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
          <button 
            onClick={handleDelete}
            className="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 flex items-center gap-2 shadow-sm"
          >
            <Trash2 size={16} /> Xóa User
          </button>
          <button 
            type="submit"
            onClick={handleSubmitForm}
            className="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2 shadow-sm"
          >
            <Save size={16} /> Lưu Người Dùng
          </button>
          <button 
            onClick={onClose}
            className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Hủy bỏ
          </button>
        </div>
      </div>
    </div>
  );
}
