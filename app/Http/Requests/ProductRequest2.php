<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Helper\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest2 extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'integer'],
            'mrp' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'status' => ['nullable'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $product = $this->route('product');
            $rules['name'] = [
                'required', 'string', 'max:255',
                Rule::unique(Product::class)->ignore($product),
            ];
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        $inputs = parent::validated();
        $inputs['status'] = $inputs['status'] ?? 0;
        $inputs['slug'] = Str::slug($inputs['name'], '-') ?: CommonHelper::makeSlug($inputs['name']);

        return $inputs;
    }
}
