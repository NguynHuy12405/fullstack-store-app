<?php

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\User\CartDetailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SizeController as AdminSizeController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\ImportController as AdminImportController;
use App\Http\Controllers\Admin\SlideShowController as AdminSlideShowController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\VariantController as AdminVariantController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//User Checkout Route
//-----------------------------



// route thong tin lien he
Route::get('/about', [ContactController::class, 'index'])->name('user.contact');
// route user help
Route::post('/help', [HelpController::class, 'store'])->name('help.store');
Route::get('/help', [HelpController::class, 'index'])->name('help.index');
//User Home Route
Route::get('/', [HomeController::class, 'index'])->name('user.home.index');
Route::get('/policy', function () {return view('user.pages.policy');})->name('policy');
Route::get('/terms', function () {return view('user.pages.terms');})->name('terms');
//-----------------------------
//User Product Route
Route::get('/san-pham', [ProductController::class, 'index'])->name('user.product.index');
Route::get('/san-pham/{product:slug}', [ProductController::class, 'show'])->name('user.product.show');
Route::get('/chat/messages', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'store']);

//-----------------------------

Route::get('/dashboard', function () {
    return view('/');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    //User ^^^^^^^^^^^^^^^^^^^^^^^^^^
    // Profile
    Route::get('/ho-so-ca-nhan', [UserProfileController::class, 'index'])->name('user.profile.index');
    // Route::get('/ho-so-ca-nhan', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    // Route::get('/ho-so-ca-nhan', [UserProfileController::class, 'update'])->name('user.profile.update');
    // Route::get('/ho-so-ca-nhan', [UserProfileController::class, 'destroy'])->name('user.profile.destroy');
    // Order
    Route::get('/ho-so-ca-nhan/don-hang',[App\Http\Controllers\OrderController::class, 'index'])->name('user.profile.order.index');
    Route::post('/ho-so-ca-nhan/don-hang/{order}/huy',[App\Http\Controllers\OrderController::class, 'cancel'])->name('user.profile.order.cancel');
    // Cart
    Route::get('/gio-hang', [CartDetailController::class, 'index'])->name('user.cart.index');
    Route::post('/gio-hang', [CartDetailController::class, 'store'])->name('user.cart.store');
    Route::delete('/gio-hang/xoa/{id}', [CartDetailController::class, 'destroy'])->name('user.cart.destroy');
    Route::patch('/gio-hang/cap-nhat/{id}', [CartDetailController::class, 'update'])->name('user.cart.update');
    Route::patch('/gio-hang/{id}', [CartDetailController::class, 'update']);
    Route::delete('/gio-hang/clear', [CartDetailController::class, 'clear'])->name('user.cart.clear');
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('user.profile.wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('user.wishlist.toggle');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'destroy'])->name('user.wishlist.destroy');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('user.wishlist.clear');
    // Checkout
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('user.checkout.index');
    Route::post('/thanh-toan', [CheckoutController::class, 'store'])
    ->name('checkout.store')
    ->middleware('auth');
    // Review
    Route::get('/reviews',[ReviewController::class,'index'])->name('user.reviews.index');
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('user.review.store');

    //Admin vvvvvvvvvvvvvvvvvvvvvvvvv
    Route::middleware('is_admin')->prefix('quan-ly')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('admin.home.index');
        // Quan ly nguoi dung và đánh giá
        Route::middleware('role:admin-user,admin-head')->group(function () {
            //users
            Route::get('/nguoi-dung', [AdminUserController::class, 'index'])->name('admin.user.index');
            Route::put('/nguoi-dung/{user}/khoa-tai-khoan', [AdminUserController::class, 'statusLock'])->name('admin.user.statusLock');
            Route::put('/nguoi-dung/{user}/mo-tai-khoan', [AdminUserController::class, 'statusOpen'])->name('admin.user.statusOpen');
            Route::put('/nguoi-dung/{user}/khoa-danh-gia', [AdminUserController::class, 'reviewLock'])->name('admin.user.reviewLock');
            Route::put('/nguoi-dung/{user}/mo-danh-gia', [AdminUserController::class, 'reviewOpen'])->name('admin.user.reviewOpen');
            Route::get('/nguoi-dung/danh-gia', [AdminUserController::class, 'review'])->name('admin.user.review');
            Route::put('/nguoi-dung/danh-gia/cap-nhat-danh-gia', [AdminUserController::class, 'updateReview'])->name('admin.user.updateReview');
            Route::get('/nguoi-dung/danh-gia/lay-danh-gia', [AdminUserController::class, 'getReviews'])->name('admin.user.getReviews');
            Route::delete('/nguoi-dung/danh-gia/xoa-danh-gia', [AdminUserController::class, 'deleteReview'])->name('admin.user.deleteReview');
            Route::put('/nguoi-dung/danh-gia/tra-loi', [AdminUserController::class, 'reply'])->name('admin.user.reply');

            // reviews
            Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('admin.reviews.reply');
        });
        // Giao hang
        Route::middleware('role:admin-shipper,admin-head')->group(function () {
            Route::get('/don-hang/nhan-don', [AdminOrderController::class, 'ship'])->name('admin.order.ship');
            Route::get('/don-hang/da-nhan', [AdminOrderController::class, 'accepted'])->name('admin.order.accepted');
        });
        // Quan ly san pham
        Route::middleware('role:admin-product,admin-head')->group(function () {
            //Product
            Route::get('/san-pham', [AdminProductController::class, 'index'])->name('admin.product.index');
            Route::get('/san-pham/thung-rac', [AdminProductController::class, 'trash'])->name('admin.product.trash');
            Route::post('/san-pham', [AdminProductController::class, 'store'])->name('admin.product.store');
            Route::delete('/san-pham/{product}/', [AdminProductController::class, 'delete'])->name('admin.product.delete');
            Route::put('/san-pham/{product}/khoi-phuc', [AdminProductController::class, 'restore'])->name('admin.product.restore')->withTrashed();
            Route::delete('/san-pham/{product}/force', [AdminProductController::class, 'forceDelete'])->name('admin.product.forceDelete')->withTrashed();
            Route::put('/san-pham/{product}/cap-nhat', [AdminProductController::class, 'update'])->name('admin.product.update');

            //Variant Route
            Route::post('/san-pham/them-bien-the', [AdminVariantController::class, 'store'])->name('admin.variant.store');
            Route::delete('/san-pham/{variant}/xoa', [AdminVariantController::class, 'delete'])->name('admin.variant.delete');

            //Category Route
            Route::get('/danh-muc', [AdminCategoryController::class, 'index'])->name('admin.category.index');
            Route::get('/danh-muc/kich-co', [AdminCategoryController::class, 'getSizes'])->name('admin.category.size');
            Route::get('/danh-muc/thung-rac', [AdminCategoryController::class, 'trash'])->name('admin.category.trash');
            Route::post('/danh-muc', [AdminCategoryController::class, 'store'])->name('admin.category.store');
            Route::delete('/danh-muc/{category}/', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
            Route::put('/danh-muc/{category}/khoi-phuc', [AdminCategoryController::class, 'restore'])->name('admin.category.restore')->withTrashed();
            Route::delete('/danh-muc/{category}/force', [AdminCategoryController::class, 'forceDelete'])->name('admin.category.forceDelete')->withTrashed();
            Route::put('/danh-muc/{category}/cap-nhat', [AdminCategoryController::class, 'update'])->name('admin.category.update');


            //Brand Route
            Route::get('/nhan-hieu', [AdminBrandController::class, 'index'])->name('admin.brand.index');
            Route::get('/nhan-hieu/thung-rac', [AdminBrandController::class, 'trash'])->name('admin.brand.trash');
            Route::post('/nhan-hieu', [AdminBrandController::class, 'store'])->name('admin.brand.store');
            Route::delete('/nhan-hieu/{brand}/', [AdminBrandController::class, 'delete'])->name('admin.brand.delete');
            Route::put('/nhan-hieu/{brand}/khoi-phuc', [AdminBrandController::class, 'restore'])->name('admin.brand.restore')->withTrashed();
            Route::delete('/nhan-hieu/{brand}/force', [AdminBrandController::class, 'forceDelete'])->name('admin.brand.forceDelete')->withTrashed();
            Route::put('/nhan-hieu/{brand}/cap-nhat', [AdminBrandController::class, 'update'])->name('admin.brand.update');

            //Color
            Route::get('/mau-sac', [AdminColorController::class, 'index'])->name('admin.color.index');
            Route::get('/mau-sac/thung-rac', [AdminColorController::class, 'trash'])->name('admin.color.trash');
            Route::post('/mau-sac', [AdminColorController::class, 'store'])->name('admin.color.store');
            Route::delete('/mau-sac/{color}/', [AdminColorController::class, 'delete'])->name('admin.color.delete');
            Route::put('/mau-sac/{color}/khoi-phuc', [AdminColorController::class, 'restore'])->name('admin.color.restore')->withTrashed();
            Route::delete('/mau-sac/{color}/force', [AdminColorController::class, 'forceDelete'])->name('admin.color.forceDelete')->withTrashed();
            Route::put('/mau-sac/{color}/cap-nhat', [AdminColorController::class, 'update'])->name('admin.color.update');

            //Size
            Route::get('/kich-co', [AdminSizeController::class, 'index'])->name('admin.size.index');
            Route::get('/kich-co/thung-rac', [AdminSizeController::class, 'trash'])->name('admin.size.trash');
            Route::post('/kich-co', [AdminSizeController::class, 'store'])->name('admin.size.store');
            Route::delete('/kich-co/{size}/', [AdminSizeController::class, 'delete'])->name('admin.size.delete');
            Route::put('/kich-co/{size}/khoi-phuc', [AdminSizeController::class, 'restore'])->name('admin.size.restore')->withTrashed();
            Route::delete('/kich-co/{size}/force', [AdminSizeController::class, 'forceDelete'])->name('admin.size.forceDelete')->withTrashed();
            Route::put('/kich-co/{size}/cap-nhat', [AdminSizeController::class, 'update'])->name('admin.size.update');

            //Tag
            Route::get('/nhan', [AdminTagController::class, 'index'])->name('admin.tag.index');
            Route::get('/nhan/thung-rac', [AdminTagController::class, 'trash'])->name('admin.tag.trash');
            Route::post('/nhan', [AdminTagController::class, 'store'])->name('admin.tag.store');
            Route::delete('/nhan/{tag}/', [AdminTagController::class, 'delete'])->name('admin.tag.delete');
            Route::put('/nhan/{tag}/khoi-phuc', [AdminTagController::class, 'restore'])->name('admin.tag.restore')->withTrashed();
            Route::delete('/nhan/{tag}/force', [AdminTagController::class, 'forceDelete'])->name('admin.tag.forceDelete')->withTrashed();
            Route::put('/nhan/{tag}/cap-nhat', [AdminTagController::class, 'update'])->name('admin.tag.update');

            //Quản lý đơn hàng
            Route::get('/don-hang', [AdminOrderController::class, 'index'])->name('admin.order.index');
            Route::get('/don-hang/{id}', [AdminOrderController::class, 'show'])->name('admin.order.show');
            Route::put('/don-hang/{order}/cap-nhat', [AdminOrderController::class, 'update'])->name('admin.order.update');
            Route::put('/don-hang/{order}/xac-nhan', [AdminOrderController::class, 'confirm'])->name('admin.order.confirm');
            Route::put('/don-hang/{order}/tu-choi', [AdminOrderController::class, 'decline'])->name('admin.order.decline');
            Route::put('/don-hang/{order}/nhan-don', [AdminOrderController::class, 'accept'])->name('admin.order.accept');
            Route::put('/don-hang/{order}/that-bai', [AdminOrderController::class, 'fail'])->name('admin.order.fail');
            Route::put('/don-hang/{order}/thanh-cong', [AdminOrderController::class, 'shipped'])->name('admin.order.shipped');

            //Quản lý nhập hàng
            Route::get('/phieu-nhap', [AdminImportController::class, 'index'])->name('admin.import.index');
            Route::get('/phieu-nhap/tao-moi', [AdminImportController::class, 'create'])->name('admin.import.create');
            Route::post('/phieu-nhap', [AdminImportController::class, 'store'])->name('admin.import.store');

        });
        // Head Admin
        Route::middleware(['role:admin-head'])->group(function () {
            Route::resource('slideshow', AdminSlideShowController::class)->names('admin.slideshow');
            //Quản lý tài khoản
            Route::get('/nguoi-dung/tai-khoan', [AdminUserController::class, 'index'])->name('admin.user.account');
            Route::get('/nguoi-dung/thung-rac', [AdminUserController::class, 'trash'])->name('admin.user.trash');
            Route::post('/nguoi-dung/tai-khoan', [AdminUserController::class, 'store'])->name('admin.user.store');
            Route::delete('/nguoi-dung/{user}/', [AdminUserController::class, 'delete'])->name('admin.user.delete');
            Route::put('/nguoi-dung/{user}/khoi-phuc', [AdminUserController::class, 'restore'])->name('admin.user.restore')->withTrashed();
            Route::delete('/nguoi-dung/{user}/force', [AdminUserController::class, 'forceDelete'])->name('admin.user.forceDelete')->withTrashed();
            Route::put('/nguoi-dung/{user}/cap-nhat', [AdminUserController::class, 'update'])->name('admin.user.update');
            //Quản lý Chức vụ
            Route::get('/chuc-vu', [AdminRoleController::class, 'index'])->name('admin.role.index');
            Route::get('/chuc-vu/thung-rac', [AdminRoleController::class, 'trash'])->name('admin.role.trash');
            Route::post('/chuc-vu', [AdminRoleController::class, 'store'])->name('admin.role.store');
            Route::delete('/chuc-vu/{role}/', [AdminRoleController::class, 'delete'])->name('admin.role.delete');
            Route::put('/chuc-vu/{role}/khoi-phuc', [AdminRoleController::class, 'restore'])->name('admin.role.restore')->withTrashed();
            Route::delete('/chuc-vu/{role}/force', [AdminRoleController::class, 'forceDelete'])->name('admin.role.forceDelete')->withTrashed();
            Route::put('/chuc-vu/{role}/cap-nhat', [AdminRoleController::class, 'update'])->name('admin.role.update');
            //Quản lý Contact
            Route::get('/lien-he', [AdminContactController::class, 'index'])->name('admin.contact.index');
            Route::get('/lien-he/thung-rac', [AdminContactController::class, 'trash'])->name('admin.contact.trash');
            Route::post('/lien-he', [AdminContactController::class, 'store'])->name('admin.contact.store');
            Route::delete('/lien-he/{contact}/', [AdminContactController::class, 'delete'])->name('admin.contact.delete');
            Route::put('/lien-he/{contact}/khoi-phuc', [AdminContactController::class, 'restore'])->name('admin.contact.restore')->withTrashed();
            Route::delete('/lien-he/{contact}/force', [AdminContactController::class, 'forceDelete'])->name('admin.contact.forceDelete')->withTrashed();
            Route::put('/lien-he/{contact}/cap-nhat', [AdminContactController::class, 'update'])->name('admin.contact.update');
        });
    });
});

Route::permanentRedirect('/admin', '/quan-ly');
Route::permanentRedirect('/product', '/san-pham');
Route::permanentRedirect('/quanly', '/quan-ly');
Route::permanentRedirect('/sanpham', '/san-pham');
Route::permanentRedirect('/dashboard', '/');



require __DIR__ . '/auth.php';
