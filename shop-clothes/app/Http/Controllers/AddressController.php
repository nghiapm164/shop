<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->orderByDesc('created_at')->paginate(10);
        return view('client.addresses.index', compact('addresses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_name' => 'required|min:2|max:100',
            'phone' => ['required', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
            'address_detail' => 'required|min:5|max:500',
        ], [
            'recipient_name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'address_detail.required' => 'Vui lòng nhập địa chỉ',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => auth()->id(),
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address_detail' => $request->address_detail,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Đã thêm địa chỉ mới.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'recipient_name' => 'required|min:2|max:100',
            'phone' => ['required', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
            'address_detail' => 'required|min:5|max:500',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update([
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address_detail' => $request->address_detail,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Đã cập nhật địa chỉ.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }
}
