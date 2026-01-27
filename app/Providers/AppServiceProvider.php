<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Review;
use App\Observers\OrderObserver;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Review::class => ReviewPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['user.layouts.app', 'user.layouts.navigation'], function ($view) {
            static $count = null;

            if ($count === null) {
                if (Auth::check()) {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    $count = $user->cartDetails()->count();
                } else {
                    // (Tuỳ chọn) Nếu bạn có lưu giỏ hàng trong Session cho khách vãng lai
                    $cartSession = session()->get('cart', []);
                    $count = is_array($cartSession) ? array_sum(array_column($cartSession, 'quantity')) : 0;
                }
            }

            $view->with('cartCount', $count);
        });

        // 2. Image Validator
        Validator::extend('shop_image', function ($attribute, $value, $parameters, $validator) {
            return $validator->validateImage($attribute, $value) &&
                $validator->validateMax($attribute, $value, [2048]) &&
                $validator->validateMimes($attribute, $value, ['jpeg', 'png', 'jpg']);
        }, 'Hình ảnh phải là định dạng jpeg, png, jpg và không quá 2MB.');

        Order::observe(OrderObserver::class);
    }
}
