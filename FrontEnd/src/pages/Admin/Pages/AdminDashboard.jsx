import { DollarSign, ShoppingBag, Users, Activity, TrendingUp } from 'lucide-react';
import Chart from "../../../components/chart/Chart";
import { stats } from "../../../mockData/data";

export default function DashboardManager() {

  const getStatStyle = (label) => {
    const lowerLabel = label.toLowerCase();
    
    if (lowerLabel.includes('doanh thu') || lowerLabel.includes('revenue') || lowerLabel.includes('total')) {
      return { 
        icon: DollarSign, 
        color: 'text-emerald-600', 
        bg: 'bg-emerald-100' 
      };
    }
    if (lowerLabel.includes('đơn hàng') || lowerLabel.includes('order')) {
      return { 
        icon: ShoppingBag, 
        color: 'text-blue-600', 
        bg: 'bg-blue-100' 
      };
    }
    if (lowerLabel.includes('khách') || lowerLabel.includes('user') || lowerLabel.includes('customer')) {
      return { 
        icon: Users, 
        color: 'text-purple-600', 
        bg: 'bg-purple-100' 
      };
    }
    return { 
      icon: Activity, 
      color: 'text-orange-600', 
      bg: 'bg-orange-100' 
    };
  };

  return (
    <div className="bg-gray-50 min-h-screen p-6">
        
        <div className="mb-8">
            <h1 className="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p className="text-gray-500 text-sm mt-1">Tổng quan tình hình kinh doanh hôm nay</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {stats.map((s, index) => {
                const style = getStatStyle(s.label);
                const IconComponent = style.icon;

                return (
                    <div 
                      key={index} 
                      className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-start justify-between"
                    >
                      <div>
                        <p className="text-gray-500 text-sm font-medium uppercase tracking-wide mb-1">
                          {s.label}
                        </p>
                        <h3 className="text-3xl font-bold text-gray-800">
                          {s.value}
                        </h3>
                        <div 
                          className={`flex items-center gap-1 mt-2 text-xs font-semibold 
                          ${s.percent >= 0 ? "text-green-600" : "text-red-600"}`}>
                          
                          <TrendingUp 
                            size={14} 
                            className={`${s.percent < 0 ? "rotate-180" : ""}`} 
                          />

                          <span>
                            {s.percent >= 0 ? "+" : ""}
                            {s.percent.toFixed(1)}% so với tháng trước
                          </span>
                        </div>
                      </div>

                      <div className={`p-4 rounded-xl ${style.bg} ${style.color}`}>
                        <IconComponent size={24} />
                      </div>
                    </div>
                );
            })}
        </div>

        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div className="flex justify-between items-center mb-6">
            <h3 className="font-bold text-gray-800 text-lg">Biểu đồ tăng trưởng</h3>
            <select className="text-sm border-gray-200 border rounded-lg p-2 text-gray-600 focus:outline-none bg-gray-50">
                <option>7 ngày qua</option>
                <option>Tháng này</option>
                <option>Năm nay</option>
            </select>
          </div>
          <Chart />
        </div>
    </div>
  );
}