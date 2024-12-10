<?php

namespace App\Http\Requests;

use App\Helper\CommonHelper;
use App\Models\ProductMedia;
use Illuminate\Foundation\Http\FormRequest;

class ProductItemRequest extends FormRequest
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
            'type' => ['required', 'in:'.ProductMedia::IMAGE.','.ProductMedia::VIDEO],
            'image' => ['required_if:type,'.ProductMedia::IMAGE ,'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['image'] = array_merge(['nullable'], CommonHelper::getImageValidationRule('image'));
        }

        return $rules;
    }
}
