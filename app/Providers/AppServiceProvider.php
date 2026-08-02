<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.customer', function ($view) {
            $cartCount = 0;
            $userId = session('user_id');
            if ($userId) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $profile = \Illuminate\Support\Facades\DB::table('customer_profiles')->where('user_id', $user->id)->first();
                    if ($profile) {
                        $cart = \Illuminate\Support\Facades\DB::table('carts')->where('customer_id', $profile->id)->first();
                        if ($cart) {
                            $cartCount = \Illuminate\Support\Facades\DB::table('cart_items')->where('cart_id', $cart->id)->sum('quantity');
                        }
                    }
                }
            }
            $view->with('cartItemCount', $cartCount);
        });
    }
}
