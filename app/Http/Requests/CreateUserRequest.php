<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "avatar" => "nullable|image|mimes:jpeg,png,jpg,gif|max:10240", // Max size: 10MB
            'products_id' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $productId) {
                        $exists = DB::table('products')->where('id', $productId)->exists();

                        if (!$exists) {
                            $fail("Product with ID $productId does not exist in the products table.");
                        }
                    }
                },
            ]
        ];
    }

    public function messages()
    {
        return [
            "avatar.image" => "The avatar must be an image.",
            "avatar.mimes" => "The avatar must be a file of type: jpeg, png, jpg, gif.",
            "avatar.max" => "The avatar may not be greater than 10 megabytes.",
        ];
    }
}
