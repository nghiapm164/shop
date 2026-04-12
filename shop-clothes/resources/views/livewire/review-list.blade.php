<div class="space-y-8">
    <!-- Reviews Summary -->
    <div class="space-y-4">
        <h3 class="text-2xl font-bold text-gray-900">Đánh giá sản phẩm</h3>
        
        @if ($this->reviewsSummary['count'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Average Rating -->
                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl font-bold text-gray-900">{{ $this->reviewsSummary['average'] }}</div>
                    <div class="flex items-center gap-1 mt-2">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 {{ $i < round($this->reviewsSummary['average']) ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                        @endfor
                    </div>
                    <div class="text-sm text-gray-500 mt-2">Dựa trên {{ $this->reviewsSummary['count'] }} đánh giá</div>
                </div>

                <!-- Rating Distribution -->
                <div class="md:col-span-3 space-y-3">
                    @for ($rating = 5; $rating >= 1; $rating--)
                        @php
                            $count = $this->reviewsSummary['distribution'][$rating];
                            $percentage = $this->reviewsSummary['count'] > 0 ? ($count / $this->reviewsSummary['count']) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600 w-12">
                                {{ $rating }}
                                <svg class="w-3 h-3 inline fill-yellow-400" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            </span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-12 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Chưa có đánh giá nào cho sản phẩm này</p>
            </div>
        @endif
    </div>

    <!-- Write Review Button -->
    <div class="border-t border-gray-200 pt-6">
        <button
            wire:click="toggleReviewForm"
            type="button"
            class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
            ✍️ Viết đánh giá
        </button>
    </div>

    <!-- Review Form -->
    @if ($this->showReviewForm)
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Gửi đánh giá của bạn</h4>
            
            <form wire:submit="submitReview" class="space-y-4">
                <!-- Rating Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Đánh giá của tôi</label>
                    <div class="flex gap-2">
                        @for ($star = 1; $star <= 5; $star++)
                            <button
                                type="button"
                                wire:click="$set('newRating', {{ $star }})"
                                class="p-2 transition-colors">
                                <svg class="w-8 h-8 {{ $star <= $this->newRating ? 'fill-yellow-400 text-yellow-400' : 'fill-gray-300 text-gray-300 hover:fill-yellow-200' }}"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                    @error('newRating')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="newComment" class="block text-sm font-semibold text-gray-900 mb-2">
                        Nhận xét (tối thiểu 10 ký tự)
                    </label>
                    <textarea
                        wire:model="newComment"
                        id="newComment"
                        rows="5"
                        placeholder="Chia sẻ trải nghiệm của bạn với sản phẩm này..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none">
                    </textarea>
                    @error('newComment')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                        Gửi đánh giá
                    </button>
                    <button
                        type="button"
                        wire:click="toggleReviewForm"
                        class="px-6 py-2 bg-gray-200 text-gray-900 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    @if ($this->reviewsSummary['count'] > 0)
        <div class="space-y-4">
            <h4 class="text-lg font-semibold text-gray-900">{{ $this->reviewsSummary['count'] }} đánh giá</h4>
            
            @forelse ($this->reviews as $review)
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">{{ $review->user->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $review->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Chưa có đánh giá nào</p>
            @endforelse

            <!-- Pagination -->
            @if ($this->reviews->hasPages())
                <div class="mt-8">
                    {{ $this->reviews->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
