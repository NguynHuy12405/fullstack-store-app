import { orders } from "../mockData/data";

const getRevenueByMonth = (month, year) => {
  return orders
    .filter(order => {
      const d = new Date(order.date);
      return (
        d.getMonth() + 1 === month &&
        d.getFullYear() === year &&
        order.status !== "Đã hủy"
      );
    })
    .reduce((sum, order) => sum + order.total, 0);
};

// Trả về mảng 12 tháng doanh thu
export const getMonthlyChartData = (year) => {
  const result = [];

  for (let month = 1; month <= 12; month++) {
    result.push({
      name: `T${month}`,
      sale: getRevenueByMonth(month, year),
    });
  }

  return result;
};


// Tính doanh thu tháng + % so sánh
export const getRevenueStats = () => {
  const latestDate = new Date(
    Math.max(...orders.map(o => new Date(o.date).getTime()))
  );

  const month = latestDate.getMonth() + 1;
  const year = latestDate.getFullYear();

  const current = getRevenueByMonth(month, year);

  const prevMonth = month === 1 ? 12 : month - 1;
  const prevYear = month === 1 ? year - 1 : year;

  const previous = getRevenueByMonth(prevMonth, prevYear);

  const percent = previous === 0 
    ? (current > 0 ? 100 : 0)
    : ((current - previous) / previous) * 100;

  return { month, year, current, previous, percent };
};

