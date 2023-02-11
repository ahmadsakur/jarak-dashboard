<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'required|uuid',
            'isSold' => 'required|boolean',
            'thumbnail' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
