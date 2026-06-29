<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Newsletter extends Component
{
    public string $email = '';
    public bool $subscribed = false;
    public string $errorMessage = '';

    public function subscribe()
    {
        $this->errorMessage = '';

        $this->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
        ]);

        // Log subscription (in production, save to DB or send to mail service)
        Log::info('Newsletter subscription', [
            'email' => $this->email,
            'ip' => request()->ip(),
        ]);

        $this->subscribed = true;
        $this->dispatch('notify', message: 'Đăng ký nhận tin thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.newsletter');
    }
}
