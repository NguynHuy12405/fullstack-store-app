<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function ajaxLogin(Request $request)
{
    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        return response()->json(['success' => true]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Email hoặc mật khẩu không đúng'
    ], 401);
}
}
