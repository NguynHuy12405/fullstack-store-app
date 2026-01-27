<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Liên hệ';
        $viewData['subtitle'] = 'Quản lý liên hệ';

        $contacts = Contact::paginate(10)->withQueryString();

        return view('admin.contact.index', compact('contacts', 'viewData'));
    }

    public function store(StoreContactRequest $request)
    {
        Contact::create($request->validated());
        return back()->with('success', "Đã thêm liên hệ mới!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        return back()->with('success', "Đã cập nhật liên hệ ID: '{$contact->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', "Đã xóa liên hệ ID: '{$contact->id}'");
    }

    public function restore(Contact $contact)
    {
        $contact->restore();
        return back()->with('success', "Khôi phục liên hệ ID: '{$contact->id}'");
    }

    public function forceDelete(Contact $contact)
    {
        $id = $contact->id;
        try {
            $contact->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn liên hệ ID: {$id}");
    }

    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Liên hệ';
        $viewData['subtitle'] = 'Quản lý liên hệ - Thùng rác';

        $contacts = Contact::onlyTrashed()->paginate(15)->withQueryString();

        return view('admin.contact.trash', compact('contacts', 'viewData'));
    }
}
