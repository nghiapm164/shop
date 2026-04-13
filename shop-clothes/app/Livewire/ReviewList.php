<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Pagination\Paginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewList extends Component
{
    use WithPagination;

    public Product $product;
    public int $newRating = 5;
    public string $newComment = '';
    public bool $showReviewForm = false;

    #[Computed]
    public function reviewsSummary()
    {
        $reviews = $this->product->reviews()->approved()->get();
        
        if ($reviews->isEmpty()) {
            return [
                'average' => 0,
                'count' => 0,
                'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
            ];
        }

        $average = $reviews->avg('rating');
        $count = $reviews->count();
        
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $reviews->where('rating', $i)->count();
        }

        return [
            'average' => round($average, 1),
            'count' => $count,
            'distribution' => $distribution,
        ];
    }

    #[Computed]
    public function reviews()
    {
        return $this->product->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->paginate(5);
    }

    #[Computed]
    public function userBoughtProduct()
    {
        if (!auth()->check()) {
            return false;
        }

        return \App\Models\Order::query()
            ->where('user_id', auth()->id())
            ->whereHas('items', function ($q) {
                $q->where('product_id', $this->product->id);
            })
            ->exists();
    }

    public function submitReview()
    {
        if (!auth()->check()) {
            $this->dispatch('notify', message: 'Vui lòng đăng nhập để gửi đánh giá', type: 'warning');
            $this->redirect('/login');
            return;
        }

        $this->validate([
            'newRating' => 'required|integer|between:1,5',
            'newComment' => 'required|min:10|max:1000',
        ], [
            'newRating.required' => 'Vui lòng chọn số sao',
            'newRating.between' => 'Số sao phải từ 1 đến 5',
            'newComment.required' => 'Vui lòng nhập nhận xét',
            'newComment.min' => 'Nhận xét phải có ít nhất 10 ký tự',
            'newComment.max' => 'Nhận xét không được vượt quá 1000 ký tự',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $this->product->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($existingReview) {
            $this->dispatch('notify', message: 'Bạn đã gửi đánh giá cho sản phẩm này rồi', type: 'warning');
            return;
        }

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => auth()->id(),
            'rating' => $this->newRating,
            'comment' => $this->newComment,
            'is_approved' => true, // Auto-approve for now, can be changed to false for moderation
        ]);

        $this->dispatch('notify', message: 'Cảm ơn bạn đã đánh giá sản phẩm!', type: 'success');
        
        // Reset form
        $this->newRating = 5;
        $this->newComment = '';
        $this->showReviewForm = false;

        // Refresh reviews
        $this->resetPage();
    }

    public function toggleReviewForm()
    {
        if (!auth()->check()) {
            $this->dispatch('notify', message: 'Vui lòng đăng nhập để gửi đánh giá', type: 'warning');
            $this->redirect('/login');
            return;
        }

        if (!$this->userBoughtProduct) {
            $this->dispatch('notify', message: 'Chỉ những khách hàng đã mua sản phẩm này mới có thể đánh giá', type: 'warning');
            return;
        }

        $this->showReviewForm = !$this->showReviewForm;
    }

    public function render()
    {
        return view('livewire.review-list');
    }
}
