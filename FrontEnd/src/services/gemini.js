import { GoogleGenerativeAI } from "@google/generative-ai";
import { useProductStore } from "../stores/useProductStore";

// --- CẤU HÌNH ---
// Bật TRUE để test giao diện mà không tốn quota. Bật FALSE để gọi AI thật.
const IS_DEV_MODE = false; 

const products = useProductStore.getState().products;

const genAI = new GoogleGenerativeAI(import.meta.env.VITE_GEN_AI_KEY);

const model = genAI.getGenerativeModel({ model: "gemini-2.0-flash" });

const SYSTEM_PROMPT = `
  Bạn là trợ lý bán hàng AI chuyên nghiệp cho một cửa hàng công nghệ.
  Phong cách: Thân thiện, ngắn gọn, dùng emoji.
  Dữ liệu sản phẩm hiện có: ${JSON.stringify(products)}

  QUY TẮC QUAN TRỌNG:
  1. Nếu người dùng hỏi tìm sản phẩm, gợi ý, hoặc giá, BẮT BUỘC trả về JSON:
  {
    "type": "product_list",
    "text": "Lời giới thiệu ngắn...",
    "productIds": [1, 2] 
  }
  2. Nếu không tìm thấy:
  {
    "type": "text",
    "text": "Rất tiếc, tôi không tìm thấy sản phẩm phù hợp."
  }
  3. Trò chuyện bình thường:
  {
    "type": "text",
    "text": "Câu trả lời của bạn..."
  }
`;

// --- 1. HÀM MOCK (GIẢ LẬP AI) ---
const callMockGemini = async (text) => {
  return new Promise((resolve) => {
    console.log("Mock AI đang xử lý: ", text);
    setTimeout(() => {
      const lowerText = text.toLowerCase();
      
      if (lowerText.includes("tai nghe") || lowerText.includes("nghe")) {
        resolve({
          type: "product_list",
          text: "Mình tìm thấy mấy mẫu tai nghe xịn xò này nè 🎧",
          productIds: [1] // ID ứng với tai nghe trong data
        });
      } else if (lowerText.includes("phím") || lowerText.includes("keyboard")) {
        resolve({
          type: "product_list",
          text: "Bàn phím cơ gõ sướng tay đây ạ ⌨️",
          productIds: [2]
        });
      } else if (lowerText.includes("màn hình")) {
        resolve({
          type: "product_list",
          text: "Màn hình 4K sắc nét cho Designer đây!",
          productIds: [4]
        });
      } else {
        resolve({
          type: "text",
          text: "Chào bạn! Đây là chế độ trả lời tự động (Mock Mode). Bạn thử hỏi về 'tai nghe' hoặc 'bàn phím' xem sao nhé!"
        });
      }
    }, 1000); // Giả lập delay 1 giây
  });
};

// --- 2. HÀM GỌI API THẬT ---
const callRealGemini = async (userMessage) => {
  try {
    const chat = model.startChat({
      history: [
        {
          role: "user",
          parts: [{ text: SYSTEM_PROMPT }],
        },
        {
          role: "model",
          parts: [{ text: `{"type": "text", "text": "OK"}` }],
        }
      ],
    });

    const result = await chat.sendMessage(userMessage);
    const responseText = result.response.text();

    // Xử lý JSON từ AI
    try {
      const cleanText = responseText.replace(/```json|```/g, '').trim();
      return JSON.parse(cleanText);
    } catch (e) {
        console.log(e);
      // Fallback nếu AI không trả đúng JSON
      return { type: 'text', text: responseText };
    }

  } catch (error) {
    // Xử lý lỗi 429 (Hết tiền/quota)
    if (error.message?.includes('429') || error.status === 429) {
        console.warn("Hết quota API.");
        return { 
            type: 'text', 
            text: "⚠️ Hệ thống đang quá tải (Lỗi 429). Vui lòng thử lại sau." 
        };
    }
    console.error("Real Gemini Error:", error);
    return { type: 'text', text: "Có lỗi kết nối với server AI." };
  }
};

// --- 3. HÀM CHÍNH (CONTROLLER) ---
export const sendMessageToGemini = async (userMessage) => {
    // Kiểm tra chế độ Dev trước tiên
    if (IS_DEV_MODE) {
        return await callMockGemini(userMessage);
    }

    // Nếu không phải Dev mode thì gọi thật
    return await callRealGemini(userMessage);
};