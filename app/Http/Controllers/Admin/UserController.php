<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - người dùng';
        $viewData['subtitle'] = 'Quản lý khách hàng';

        $users = User::query()->filter($request->all())->where('role_id', 1)->Paginate(10)->withQueryString();

        $counts = User::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.user.index', compact('users', 'viewData', 'counts'));
    }

    public function reviewLock(User $user)
    {
        $user->update(['review' => 0]);
        return back()->with('success', "Đã cập nhật tài khoản cho người dùng: '{$user->name}' ID: '{$user->id}'!");
    }
    public function reviewOpen(User $user)
    {
        $user->update(['review' => 1]);
        return back()->with('success', "Đã cập nhật tài khoản cho người dùng: '{$user->name}' ID: '{$user->id}'!");
    }
    public function statusLock(User $user)
    {
        $user->update(['status' => 0]);
        return back()->with('success', "Đã cập nhật tài khoản cho người dùng: '{$user->name}' ID: '{$user->id}'!");
    }
    public function statusOpen(User $user)
    {
        $user->update(['status' => 1]);
        return back()->with('success', "Đã cập nhật tài khoản cho người dùng: '{$user->name}' ID: '{$user->id}'!");
    }

    public function account(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - người dùng';
        $viewData['subtitle'] = 'Quản lý tài khoản';

        $users = User::query()->filter($request->all())->Paginate(10)->withQueryString();

        $roles = Role::all();

        $counts = User::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.user.account', compact('users', 'viewData', 'counts', 'roles'));
    }

    public function review(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - người dùng';
        $viewData['subtitle'] = 'Quản lý đánh giá';
        $products = Product::query()->filter($request->all())->orderByDesc(
            Review::select('created_at')
                ->whereColumn('product_id', 'products.id')
                ->latest()
                ->take(1)
        )->with(['reviews.replier', 'reviews.user'])->withCount('reviews')->Paginate(10)->withQueryString();
        return view('admin.user.review', compact('products', 'viewData'));
    }

    public function updateReview(Request $request)
    {
        $review = Review::find($request->id);
        $review->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function getReviews(Request $request){
        $product = Product::with('reviews.user')->find($request->id);
        return response()->json(['success' => true, 'data' => $product->reviews]);
    }

    public function deleteReview(Request $request)
    {
        $review = Review::find($request->id);
        if($review) $review->delete();
        return response()->json(['success' => true]);
    }

    public function reply(Request $request)
    {
        $request->validate([
            'reply' => 'nullable|string|max:1000',
        ]);
        $review = Review::find($request->id);
        $review->update(['reply' => $request->reply, 'replier' => Auth::id(), 'replied_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return back()->with('success', "Đã tạo tài khoản cho người dùng: '{$user->name}', ID: '{$user->id}'!");
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return back()->with('success', "Đã cập nhật tài khoản cho người dùng: '{$user->name}' ID: '{$user->id}'!");
    }

    public function delete(User $user)
    {
        $user->delete();
        return back()->with('success', "Đã xóa người dùng '{$user->name}' ID: '{$user->id}'");
    }
    public function restore(User $user)
    {
        $user->restore();
        return back()->with('success', "Khôi phục người dùng '{$user->name}' ID: '{$user->id}'");
    }
    public function forceDelete(User $user)
    {
        $name = $user->name;
        $id = $user->id;
        try {
            $user->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn người dùng {$name} ID: {$id}");
    }
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Người dùng';
        $viewData['subtitle'] = 'Quản lý tài khoản - Thùng rác';

        $users = User::query()->filter($request->all())->with('role')->onlyTrashed()->paginate(15)->withQueryString();

        $roles = Role::all();

        return view('admin.user.trash', compact('users', 'viewData', 'roles'));
    }
}
