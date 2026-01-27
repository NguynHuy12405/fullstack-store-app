import { useState, useRef, useEffect } from 'react';
import { BotMessageSquare, X, Send, ShoppingBag, Loader2 } from 'lucide-react';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { useChatStore } from '../../stores/useChatStore';
import { sendMessageToGemini } from '../../services/gemini';


const cn = (...inputs) => twMerge(clsx(inputs));
const quickSuggestions = [
  "Gợi ý Outfit cho sinh viên",
  "Tìm Áo Thun chất lượng tốt",
  "Sản phẩm thời trang mới nhất"
];

const ChatWidget = () => {
  const { isOpen, toggleChat, messages, addMessage, isLoading, setLoading } = useChatStore();
  const [input, setInput] = useState('');
  const messagesEndRef = useRef(null);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages, isOpen]);

const handleSend = async (e) => {
    e.preventDefault();
    if (!input.trim() || isLoading) return;

    const userText = input;
    setInput('');
    addMessage({ role: 'user', content: userText, type: 'text' });
    setLoading(true);

    try {
        const response = await sendMessageToGemini(userText);
        
        if (response.type === 'text' && response.text.includes('429')) {
             addMessage({ 
                role: 'assistant', 
                content: "⚠️ Hệ thống đang bận (429). Đang chuyển sang chế độ tìm kiếm cơ bản...", 
                type: 'text' 
            });
        } else {
            if (response.type === 'product_list') {
                addMessage({ 
                    role: 'assistant', 
                    content: response.text, 
                    type: 'product_list',
                    productIds: response.productIds 
                });
            } else {
                addMessage({ 
                    role: 'assistant', 
                    content: response.text || response.content,
                    type: 'text' 
                });
            }
        }
    } catch (err) {
      console.log(err);
        addMessage({ 
            role: 'assistant', 
            content: "Xin lỗi, kết nối không ổn định.", 
            type: 'text' 
        });
    } finally {
        setLoading(false);
    }
};

  return (
    <div className="fixed bottom-6 right-6 z-10 flex flex-col items-end font-sans">
      {/* Chat Window */}
      <div 
        className={cn(
          "bg-gray-50 w-[360px] h-[550px] rounded-2xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300 ease-in-out transform origin-bottom-right mb-4 border border-gray-200",
          isOpen ? "scale-100 opacity-100 translate-y-0" : "scale-0 opacity-0 translate-y-10 pointer-events-none"
        )}
      >
        {/* Header */}
        <div className="bg-orange-500 p-4 flex justify-between items-center text-white shadow-md">
          <div className="flex items-center gap-2">
            <div className="bg-white/20 p-2 rounded-full backdrop-blur-sm">
              <ShoppingBag size={20} className="text-white" />
            </div>
            <div>
              <h3 className="font-bold text-lg leading-tight">Trợ lý AI</h3>
              <p className="text-orange-100 text-xs">Luôn sẵn sàng hỗ trợ</p>
            </div>
          </div>
          <button onClick={toggleChat} className="hover:bg-white/20 p-1 rounded-full transition-colors">
            <X size={20} />
          </button>
        </div>

        {/* Messages Body */}
        <div className="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar bg-white modern-scroll">
          {messages.map((msg) => (
            <div key={msg.id} className={cn("flex flex-col max-w-[85%]", msg.role === 'user' ? "ml-auto items-end" : "mr-auto items-start")}>
              <div 
                className={cn(
                  "p-3 rounded-2xl text-sm shadow-sm",
                  msg.role === 'user' 
                    ? "bg-black text-white rounded-br-none" 
                    : "bg-gray-100 text-gray-800 rounded-bl-none border border-gray-200"
                )}
              >
                {msg.content}
              </div>

              {msg.type === 'product_list' && msg.productIds && (
                <div className="mt-2 w-full animate-fadeIn">
                  {msg.productIds.map(id => (
                    <ProductCard key={id} productId={id} />
                  ))}
                </div>
              )}
              
              <span className="text-[10px] text-gray-400 mt-1 px-1">
                {msg.role === 'assistant' ? 'AI Support' : 'You'}
              </span>
            </div>
          ))}

          {/* Loading Indicator */}
          {isLoading && (
            <div className="flex items-center gap-2 text-gray-400 text-xs ml-2">
              <div className="w-2 h-2 bg-orange-500 rounded-full animate-bounce" style={{ animationDelay: '0s' }}></div>
              <div className="w-2 h-2 bg-orange-500 rounded-full animate-bounce" style={{ animationDelay: '0.2s' }}></div>
              <div className="w-2 h-2 bg-orange-500 rounded-full animate-bounce" style={{ animationDelay: '0.4s' }}></div>
            </div>
          )}

          {messages.length === 1 && (
              <div className="space-y-2 animate-fadeIn">
                <p className="text-xs text-gray-500 font-semibold px-2">Gợi ý nhanh:</p>
                {quickSuggestions.map((suggestion, idx) => (
                  <button
                    key={idx}
                    onClick={() => setInput(suggestion)}
                    className="w-full text-left p-3 bg-white hover:bg-orange-50 border border-orange-200 rounded-xl text-sm text-gray-700 transition-all duration-200 hover:border-orange-400 hover:shadow-md flex items-center gap-2 group"
                  >
                    <ShoppingBag className="w-4 h-4 text-orange-500 group-hover:scale-110 transition-transform" />
                    {suggestion}
                  </button>
                ))}
              </div>
            )}
            
          <div ref={messagesEndRef} />
        </div>

        {/* Input Area */}
        <form onSubmit={handleSend} className="p-3 bg-gray-50 border-t border-gray-200 flex gap-2 items-center">
          <input
            type="text"
            value={input}
            onChange={(e) => setInput(e.target.value)}
            placeholder="Soạn Tin..."
            className="flex-1 bg-white border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all text-gray-800 placeholder-gray-400"
          />
          <button 
            type="submit" 
            disabled={isLoading || !input.trim()}
            className="bg-black text-white p-2.5 rounded-full hover:bg-orange-500 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors shadow-md"
          >
            {isLoading ? <Loader2 size={18} className="animate-spin" /> : <Send size={18} />}
          </button>
        </form>
      </div>

      {/* Toggle Button */}
      <button
        onClick={toggleChat}
        className={cn(
          "p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 flex items-center justify-center relative group cursor-pointer",
          isOpen ? "bg-black rotate-90" : "bg-orange-500 rotate-0"
        )}
      >
        {isOpen ? (
          <X size={24} className="text-white cursor-pointer" />
        ) : (
          <>
             <BotMessageSquare size={24} className="text-white cursor-pointer hover:rotate-45" />
             <span className="absolute -top-1 -right-1 flex h-4 w-4">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-200 opacity-75" />
                <span className="relative inline-flex rounded-full h-4 w-4 bg-green-500 border-2 border-yellow-500" />
             </span>
          </>
        )}
      </button>

      <style>{`
        @keyframes slideUp {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(10px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        .animate-slideUp {
          animation: slideUp 0.3s ease-out;
        }
        
        .animate-fadeIn {
          animation: fadeIn 0.3s ease-out;
        }
      `}</style>
    </div>

  );
};

export default ChatWidget;