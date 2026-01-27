<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    // Lấy danh sách tin nhắn
    public function index() {
        $sessionId = Session::getId();
        return ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Gửi tin nhắn
    public function store(Request $request) {
        $request->validate(['message' => 'required|string']);

        $chat = ChatMessage::create([
            'session_id' => Session::getId(),
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        return response()->json($chat);
    }
}
