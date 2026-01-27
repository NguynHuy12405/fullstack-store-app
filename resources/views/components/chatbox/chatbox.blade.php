<div x-data="chatbox()" x-init="initChat()" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2">
    {{-- 1. CHAT WINDOW --}}
    <div x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="w-[350px] bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col mb-2"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-orange-500 to-yellow-400 p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-headset text-lg"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-yellow-300 border-2 border-green-600 rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Hỗ trợ khách hàng</h3>
                    <p class="text-[10px] text-gray-800 opacity-90">Chúng tôi thường trả lời ngay</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white/80 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Messages Body --}}
        <div class="h-[350px] bg-gray-50 p-4 overflow-y-auto space-y-3" x-ref="chatBody">
            <div class="flex justify-start">
                <div class="bg-white border border-gray-200 text-gray-600 text-sm py-2 px-3 rounded-tr-xl rounded-br-xl rounded-bl-xl shadow-sm max-w-[85%]">
                    Xin chào! 👋 Chúng tôi có thể giúp gì cho bạn hôm nay?
                </div>
            </div>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.is_admin ? 'justify-start' : 'justify-end'">
                    <div class="text-sm py-2 px-3 shadow-sm max-w-[85%]"
                         :class="msg.is_admin 
                            ? 'bg-white border border-gray-400 text-black rounded-tr-xl rounded-br-xl rounded-bl-xl' 
                            : 'bg-[#d9d99b] text-black rounded-tl-xl rounded-bl-xl rounded-br-xl'">
                        <span x-text="msg.message"></span>
                        <div class="text-[9px] mt-1 text-right opacity-70" x-text="formatTime(msg.created_at)"></div>
                    </div>
                </div>
            </template>

            <div x-show="isSending" class="flex justify-end">
                <div class="bg-gray-200 text-gray-500 text-xs py-1 px-3 rounded-full animate-pulse">
                    Đang gửi...
                </div>
            </div>
        </div>

        {{-- Footer Input --}}
        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="relative flex items-center gap-2">
                <input type="text" x-model="newMessage" 
                       class="w-full bg-gray-50 border-gray-200 rounded-full pl-4 pr-10 py-2.5 text-sm focus:border-gray-500 focus:ring-0 transition" 
                       placeholder="Nhập tin nhắn..." 
                       :disabled="isSending">
                
                <button type="submit" 
                        class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center bg-[#d9d99b] hover:bg-[#cccc60] text-white rounded-full shadow-md transition-transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!newMessage.trim() || isSending">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- 2. TRIGGER BUTTON (FAB) --}}
    <button @click="isOpen = !isOpen" 
        class="group w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 focus:outline-none"
        :class="isOpen ? 'bg-gray-100 text-black rotate-90' : 'bg-black text-white hover:bg-gray-700'"
    >
        
        <i x-show="!isOpen" class="fas fa-comment-dots text-2xl transition-transform duration-300"></i>
        <i x-show="isOpen" class="fas fa-chevron-down text-xl transition-transform duration-300"></i>

        <span x-show="!isOpen" class="absolute top-0 right-0 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
    </button>

</div>

<script>
    function chatbox() {
        return {
            isOpen: false,
            messages: [],
            newMessage: '',
            isSending: false,

            initChat() {
            console.log('Chatbox initiated');
                this.fetchMessages();
                setInterval(() => {
                    if(this.isOpen) this.fetchMessages();
                }, 5000);
            },

            fetchMessages() {
                fetch('/chat/messages')
                    .then(res => res.json())
                    .then(data => {
                        // Chỉ cập nhật và scroll nếu có tin nhắn mới
                        if (data.length > this.messages.length) {
                            this.messages = data;
                            this.scrollToBottom();
                        }
                    });
            },

            sendMessage() {
                if (!this.newMessage.trim()) return;

                this.isSending = true;
                const messageToSend = this.newMessage;
                this.newMessage = ''; // Reset input ngay lập tức cho mượt

                // Fake UI update ngay lập tức (Optimistic UI)
                this.messages.push({
                    id: Date.now(),
                    message: messageToSend,
                    is_admin: 0,
                    created_at: new Date().toISOString()
                });
                this.scrollToBottom();

                // Gửi lên server
                fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: messageToSend })
                })
                .then(res => res.json())
                .then(data => {
                    this.isSending = false;
                    // Thực tế có thể cập nhật lại ID thật từ server nếu cần
                })
                .catch(() => {
                    this.isSending = false;
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                });
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const chatBody = this.$refs.chatBody;
                    chatBody.scrollTop = chatBody.scrollHeight;
                });
            },

            formatTime(dateString) {
                const date = new Date(dateString);
                return date.getHours() + ':' + (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
            }
        }
    }
</script>