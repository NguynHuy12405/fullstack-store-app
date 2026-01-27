import { User, Search, Edit, Trash2, BookUser, UserCog } from 'lucide-react';
import { useAuthStore } from '../../../stores/useAuthStore';
import React, { useMemo } from 'react';
import StatCard from "../../../components/stat/StatCard";
import EditUser from "../../../components/form/CRUD/EditUser";
import { useUIStore } from '../../../stores/useUIStore';
import Pagination from '../../../components/pagination/Pagination';

const STATUS_OPTIONS = ["Tất cả", "Admin", "User"];
const PAGE_SIZE = 10;

export default function UserManager() {
  const { users, updateUser, deleteUser } = useAuthStore();
  const { statusFilter, setStatusFilter, currentPage, setCurrentPage, toggleEditModal, isEditOpen, selectedUser, setSelectedUser } = useUIStore();

  // Lọc theo trạng thái
  const filteredUser = useMemo(() => {
    if (statusFilter === "Tất cả") return users;
    return users.filter(o => o.role === statusFilter);
  }, [users, statusFilter]);

  // Phân trang
  const totalPages = Math.ceil(filteredUser.length / PAGE_SIZE);
  const paginatedUsers = filteredUser.slice(
    (currentPage - 1) * PAGE_SIZE,
    currentPage * PAGE_SIZE
  );

  const handleEditClick = (user) => {
    setSelectedUser(user);
    toggleEditModal(true);
  };

  const handleSaveUser = (updatedUser) => {
    updateUser(updatedUser);
  };

  const handleDeleteUser = (userId) => {
    if (confirm("Bạn có chắc muốn xóa user này?")) {
      deleteUser(userId);
    }
  };

  const handlePaginate = (pageNumber) => {
    setCurrentPage(pageNumber);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <div className="p-8 bg-gray-50 min-h-screen">
      <div className="flex justify-start items-center mb-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Quản lý người dùng</h1>
          <p className="text-sm text-gray-500 mt-1">Danh sách tất cả thành viên trong hệ thống</p>
        </div>
      </div>

      {/* Thống kê */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <StatCard label="Tổng Người Dùng" value={users.length} icon={BookUser} color="bg-blue-500" />
          <StatCard label="Tổng số Users" value={users.filter(u => u.role === "User").length} icon={User} color="bg-yellow-500" />
          <StatCard label="Tổng số Admins" value={users.filter(u => u.role === "Admin").length} icon={UserCog} color="bg-green-500" />
        </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div className="p-4 border-b border-gray-100 flex gap-4">
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
            <input 
              type="text" 
              placeholder="Tìm kiếm theo tên, email..." 
              className="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
            />
          </div>
          <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)} className="px-4 py-2 border rounded-lg text-sm text-gray-600 focus:outline-none cursor-pointer">
            {STATUS_OPTIONS.map(roles => (
            <option key={roles} value={roles}>{roles}</option>
          ))}
          </select>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead className="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
              <tr>
                <th className="px-6 py-4">Thành viên</th>
                <th className="px-6 py-4">Quyền hạn</th>
                <th className="px-6 py-4">Trạng thái</th>
                <th className="px-6 py-4 text-right">Hành động</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {paginatedUsers.map((user) => (
                <React.Fragment key={user.id}>
                  <tr className="hover:bg-gray-50 transition">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <img src={user.avatar} alt="" className="w-10 h-10 rounded-full border border-gray-200" />
                        <div>
                          <div className="font-medium text-gray-800">{user.name}</div>
                          <div className="text-xs text-gray-500">{user.email}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span className="text-sm text-gray-600">{user.role}</span>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        ${user.status === 'Active' ? 'bg-blue-100 text-blue-700' : 
                          user.status === 'Inactive' ? 'bg-gray-200 text-gray-700' : 'bg-red-200 text-red-800'}`}>
                        {user.status === 'Active' ? 'Hoạt động' : user.status === 'Inactive' ? 'Ngoại tuyến' : 'Đã khóa'}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex justify-end gap-2">
                        <button 
                          onClick={() => handleEditClick(user)}
                          className="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition cursor-pointer"
                        >
                          <Edit size={18} />
                        </button>
                        <button 
                          onClick={() => handleDeleteUser(user.id)}
                          className="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer"
                        >
                          <Trash2 size={18} />
                        </button>
                      </div>
                    </td>
                  </tr>
                </React.Fragment>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Pagination */}
        <div className="p-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
          <Pagination totalPages={totalPages} currentPage={currentPage} onPageChange={handlePaginate} />
        </div>
      </div>
      <EditUser 
        isOpen={isEditOpen} 
        onClose={() => toggleEditModal(false)} 
        user={selectedUser} 
        onSave={handleSaveUser} 
      />
    </div>
  );
}
