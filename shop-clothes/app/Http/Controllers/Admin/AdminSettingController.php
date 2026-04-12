<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $payload = (array) $request->input('settings', []);

        foreach ($payload as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) {
                continue;
            }

            if ($setting->type === 'boolean') {
                $normalized = in_array((string) $value, ['1', 'true', 'on', 'yes'], true) ? '1' : '0';
                $setting->update(['value' => $normalized]);
                continue;
            }

            $setting->update(['value' => is_array($value) ? json_encode($value) : (string) $value]);
        }

        return back()->with('success', 'Đã cập nhật cài đặt hệ thống.');
    }
}
