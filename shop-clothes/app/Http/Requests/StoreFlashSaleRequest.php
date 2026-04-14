<?php

namespace App\Http\Requests;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreFlashSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'exists:products,id'],
            'flash_price' => ['required', 'numeric', 'min:1000'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $product = Product::find($this->input('product_id'));

            if (!$product) {
                return;
            }

            $basePrice = (float) ($product->sale_price ?? $product->price);
            $flashPrice = (float) $this->input('flash_price');

            if ($flashPrice >= $basePrice) {
                $validator->errors()->add('flash_price', 'Flash sale price must be lower than the current product price.');
            }

            $hasOverlap = FlashSale::query()
                ->where('product_id', $product->id)
                ->where(function ($query) {
                    $query
                        ->whereBetween('start_at', [$this->input('start_at'), $this->input('end_at')])
                        ->orWhereBetween('end_at', [$this->input('start_at'), $this->input('end_at')])
                        ->orWhere(function ($subQuery) {
                            $subQuery
                                ->where('start_at', '<=', $this->input('start_at'))
                                ->where('end_at', '>=', $this->input('end_at'));
                        });
                })
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add('product_id', 'This product already has a flash sale in the selected timeframe.');
            }
        });
    }
}
