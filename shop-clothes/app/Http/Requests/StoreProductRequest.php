<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare input before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],

            'variants' => ['required', 'array', 'min:1'],
            'variants.*.size_id' => ['required', 'exists:sizes,id'],
            'variants.*.color_id' => ['required', 'exists:colors,id'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.additional_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'variants.required' => 'Vui lòng tạo ít nhất 1 biến thể.',
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá bán.',
            'meta_title.max' => 'Meta title tối đa 60 ký tự.',
            'meta_description.max' => 'Meta description tối đa 160 ký tự.',
        ];
    }
}
