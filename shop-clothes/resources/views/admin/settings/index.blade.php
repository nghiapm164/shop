@extends('layouts.admin')

@section('page-title', 'Cài đặt hệ thống')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cài đặt hệ thống</h1>
            <p class="text-sm text-gray-500">Cấu hình thông tin cửa hàng, liên hệ, và hành vi hiển thị.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        @forelse ($settings as $group => $items)
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $group)) }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($items as $setting)
                        <div class="{{ $setting->type === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $setting->label }}</label>

                            @if ($setting->type === 'textarea')
                                <textarea name="settings[{{ $setting->key }}]" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @elseif ($setting->type === 'boolean')
                                <select name="settings[{{ $setting->key }}]" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                                    <option value="1" @selected((string) old('settings.' . $setting->key, $setting->value) === '1')>Bật</option>
                                    <option value="0" @selected((string) old('settings.' . $setting->key, $setting->value) === '0')>Tắt</option>
                                </select>
                            @else
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center text-gray-500">
                Chưa có cấu hình nào. Hãy chạy seeder để khởi tạo dữ liệu mặc định.
            </div>
        @endforelse

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Lưu cài đặt</button>
        </div>
    </form>
</div>
@endsection
