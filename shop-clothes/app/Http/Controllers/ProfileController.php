<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update user's personal information (name, phone, avatar)
     */
    public function updatePersonal(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+\d{1,3}[- ]?)?\d{10,}$/', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'avatar' => $user->avatar,
        ]);

        return Redirect::route('profile.edit')->with('status', 'personal-info-updated');
    }

    /**
     * Change user's password
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Display user's addresses
     */
    public function addresses(Request $request): View
    {
        return view('profile.addresses.index', [
            'addresses' => $request->user()->addresses()->get(),
            'defaultAddress' => $request->user()->defaultAddress,
        ]);
    }

    /**
     * Show form to create new address
     */
    public function createAddress(): View
    {
        return view('profile.addresses.create');
    }

    /**
     * Store new address
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+\d{1,3}[- ]?)?\d{10,}$/', 'max:20'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'ward' => ['required', 'string', 'max:255'],
            'address_detail' => ['required', 'string', 'max:500'],
            'is_default' => ['boolean'],
        ]);

        $address = $request->user()->addresses()->create($request->validated());

        if ($request->boolean('is_default')) {
            $address->makeDefault();
        }

        return Redirect::route('profile.addresses')->with('status', 'address-created');
    }

    /**
     * Show form to edit address
     */
    public function editAddress(Address $address): View
    {
        $this->authorize('update', $address);
        
        return view('profile.addresses.edit', ['address' => $address]);
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+\d{1,3}[- ]?)?\d{10,}$/', 'max:20'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'ward' => ['required', 'string', 'max:255'],
            'address_detail' => ['required', 'string', 'max:500'],
            'is_default' => ['boolean'],
        ]);

        $address->update($request->validated());

        if ($request->boolean('is_default')) {
            $address->makeDefault();
        }

        return Redirect::route('profile.addresses')->with('status', 'address-updated');
    }

    /**
     * Delete address
     */
    public function destroyAddress(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return Redirect::route('profile.addresses')->with('status', 'address-deleted');
    }

    /**
     * Display user's order history
     */
    public function orders(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('items.productVariant.product')
            ->latest()
            ->paginate(10);

        return view('profile.orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Display single order details
     */
    public function showOrder(Order $order): View
    {
        $this->authorize('view', $order);

        return view('profile.orders.show', [
            'order' => $order->load('items.productVariant.product', 'items.productVariant.size', 'items.productVariant.color'),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
