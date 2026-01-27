<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Help;
use App\Mail\HelpMail;
use Illuminate\Support\Facades\Mail;
class HelpController extends Controller
{
    public function index()
    {
        return view('emails.index');
    }
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string',
        'message' => 'required|string',
    ]);

    Help::create($data);

    // Gửi email cho khách
    Mail::to($data['email'])->send(new HelpMail($data));

    return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
}
}
