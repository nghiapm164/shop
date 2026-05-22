<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display the authenticated user's wishlist.
     */
    public function index()
    {
        $wishlistProducts = auth()->user()
            ->wishlist()
            ->with(['category', 'images', 'variants.color', 'variants.size', 'reviews'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('client.wishlist', compact('wishlistProducts'));
    }

    /**
     * Toggle a product in the wishlist (add if not exists, remove if exists).
     */
    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng tính năng yêu thích.',
            ], 401);
        }

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();

            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích.',
                'wishlist_count' => $user->wishlist()->count(),
            ]);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'action' => 'added',
            'message' => 'Đã thêm vào danh sách yêu thích.',
            'wishlist_count' => $user->wishlist()->count(),
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $user = auth()->user();

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }
}