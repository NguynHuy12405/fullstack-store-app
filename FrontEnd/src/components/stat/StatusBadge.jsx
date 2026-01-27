
export default function StatusBadge ({ status }) {
  const styles = {
    // Sản phẩm
    'Còn hàng': 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'Sắp hết': 'bg-amber-100 text-amber-700 border-amber-200',
    'Hết hàng': 'bg-rose-100 text-rose-700 border-rose-200',
    // Đơn hàng
    'Hoàn thành': 'bg-blue-100 text-blue-700 border-blue-200',
    'Đang xử lý': 'bg-yellow-50 text-yellow-700 border-yellow-200',
    'Đang giao': 'bg-purple-100 text-purple-700 border-purple-200',
    'Giao thành công': 'px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs',
    'Đã hủy': 'bg-gray-100 text-gray-600 border-gray-200',
  };

  return (
    <span className={`px-3 py-1 rounded-full text-xs font-semibold border ${styles[status] || 'bg-gray-100 text-gray-600'}`}>
      {status}
    </span>
  );
};