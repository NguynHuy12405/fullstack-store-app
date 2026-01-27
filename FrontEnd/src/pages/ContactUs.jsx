import React, { useState } from 'react';
import { MapPin, Phone, Mail, Instagram, Facebook, Twitter } from 'lucide-react';
import ButtonLink from '../components/Button/ButtonLink';


export default function ContactSection () {
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    message: ''
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Form submitted:', formData);
    alert('Cảm ơn bạn! Chúng tôi đã nhận được tin nhắn.');
  };

  return (
    <section className="relative w-full bg-white py-10 md:py-14 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="mb-10 text-center md:text-left">
          <h2 className="text-4xl md:text-5xl font-bold text-black mb-4">
            Liên hệ với chúng tôi
          </h2>
          <div className="h-1 w-20 bg-[#D2B48C] md:mx-0 mx-auto" />
        </div>

        <div className="flex flex-col lg:flex-row gap-12 lg:gap-20">
          <div className="w-full lg:w-5/12 bg-[#F5F0EB] p-8 md:p-12 rounded-lg relative group">
            <div className="absolute top-0 right-0 w-24 h-24 border-t-2 border-r-2 border-black/10 rounded-tr-lg -mt-2 -mr-2 transition-all group-hover:mt-0 group-hover:mr-0"></div>
            <div className="absolute bottom-0 left-0 w-24 h-24 border-b-2 border-l-2 border-black/10 rounded-bl-lg -mb-2 -ml-2 transition-all group-hover:mb-0 group-hover:ml-0"></div>
            <h3 className="text-2xl font-bold text-black mb-6">Thông tin</h3>
            <p className="text-gray-600 mb-10 leading-relaxed font-light">
              Chúng tôi trân trọng từng ý kiến đóng góp của bạn. Đừng ngần ngại liên hệ để nhận được sự tư vấn tốt nhất.
            </p>

            <div className="space-y-8">
              {/* Item: Địa chỉ */}
              <div className="flex gap-5 items-start">
                <div className="p-3 bg-white border border-black/5 rounded-full shadow-sm text-black shrink-0">
                  <MapPin size={20} strokeWidth={1.5} />
                </div>
                <div>
                  <h4 className="font-bold text-sm uppercase tracking-wide text-black mb-1">Trụ sở chính</h4>
                  <p className="text-gray-600 text-sm">123 Đường Pasteur, Quận 1,<br/>TP. Hồ Chí Minh, Việt Nam</p>
                </div>
              </div>

              {/* Item: Email */}
              <div className="flex gap-5 items-start">
                <div className="p-3 bg-white border border-black/5 rounded-full shadow-sm text-black shrink-0">
                  <Mail size={20} strokeWidth={1.5} />
                </div>
                <div>
                  <h4 className="font-bold text-sm uppercase tracking-wide text-black mb-1">Email</h4>
                  <p className="text-gray-600 text-sm">support@brandname.com</p>
                  <p className="text-gray-600 text-sm">hello@brandname.com</p>
                </div>
              </div>

              {/* Item: Điện thoại */}
              <div className="flex gap-5 items-start">
                <div className="p-3 bg-white border border-black/5 rounded-full shadow-sm text-black shrink-0">
                  <Phone size={20} strokeWidth={1.5} />
                </div>
                <div>
                  <h4 className="font-bold text-sm uppercase tracking-wide text-black mb-1">Hotline</h4>
                  <p className="text-gray-600 text-sm font-medium">+84 (0) 909 123 456</p>
                  <p className="text-xs text-gray-400 mt-1">Thứ 2 - Thứ 6: 8am - 5pm</p>
                </div>
              </div>
            </div>

            {/* Social Links */}
            <div className="mt-12 flex gap-4 pt-8 border-t border-black/5">
              {[Facebook, Instagram, Twitter].map((Icon, idx) => (
                <a key={idx} href="#" className="p-2 text-gray-500 hover:text-black hover:bg-white rounded-full transition-all duration-300">
                  <Icon size={20} strokeWidth={1.5} />
                </a>
              ))}
            </div>
          </div>

          <div className="w-full lg:w-7/12 flex flex-col justify-center">
            <h3 className="text-2xl font-inter font-bold text-black mb-2">Gửi tin nhắn</h3>
            <p className="text-gray-500 text-sm mb-8">Vui lòng điền vào biểu mẫu, chúng tôi sẽ phản hồi trong 24h.</p>

            <form onSubmit={handleSubmit} className="space-y-8">
              <div className="grid md:grid-cols-2 gap-8">
                <div className="group relative">
                  <input 
                    type="text" 
                    name="firstName" 
                    id="firstName"
                    value={formData.firstName}
                    onChange={handleChange}
                    className="peer w-full border-b border-gray-300 py-3 bg-transparent text-black focus:outline-none focus:border-black transition-colors placeholder-transparent"
                    placeholder="Tên"
                    required
                  />
                  <label htmlFor="firstName" className="absolute left-0 -top-3.5 text-xs text-gray-400 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-[#D2B48C] font-medium uppercase tracking-wider cursor-text">
                    Tên
                  </label>
                </div>

                <div className="group relative">
                  <input 
                    type="text" 
                    name="lastName" 
                    id="lastName"
                    value={formData.lastName}
                    onChange={handleChange}
                    className="peer w-full border-b border-gray-300 py-3 bg-transparent text-black focus:outline-none focus:border-black transition-colors placeholder-transparent"
                    placeholder="Họ"
                    required
                  />
                  <label htmlFor="lastName" className="absolute left-0 -top-3.5 text-xs text-gray-400 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-[#D2B48C] font-medium uppercase tracking-wider cursor-text">
                    Họ
                  </label>
                </div>
              </div>

              <div className="group relative">
                <input 
                  type="email" 
                  name="email" 
                  id="email"
                  value={formData.email}
                  onChange={handleChange}
                  className="peer w-full border-b border-gray-300 py-3 bg-transparent text-black focus:outline-none focus:border-black transition-colors placeholder-transparent"
                  placeholder="Email"
                  required
                />
                <label htmlFor="email" className="absolute left-0 -top-3.5 text-xs text-gray-400 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-[#D2B48C] font-medium uppercase tracking-wider cursor-text">
                  Địa chỉ Email
                </label>
              </div>

              <div className="group relative">
                <textarea 
                  name="message" 
                  id="message"
                  value={formData.message}
                  onChange={handleChange}
                  rows="4"
                  className="peer w-full border-b border-gray-300 py-3 bg-transparent text-black focus:outline-none focus:border-black transition-colors placeholder-transparent resize-none"
                  placeholder="Lời nhắn"
                  required
                ></textarea>
                <label htmlFor="message" className="absolute left-0 -top-3.5 text-xs text-gray-400 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-[#D2B48C] font-medium uppercase tracking-wider cursor-text">
                  Lời nhắn của bạn
                </label>
              </div>

              <ButtonLink title={"Gửi tin nhắn "} />
            </form>
          </div>
        </div>
      </div>
    </section>
  );
};

