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
        if (request()->server('HTTP_X_FORWARDED_PROTO') == 'https' || str_contains(request()->header('host'), 'loca.lt')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Yêu cầu đặt lại mật khẩu - MODESPORT')
                ->view('emails.auth.reset-password', ['url' => $url, 'user' => $notifiable]);
        });
    }
}
