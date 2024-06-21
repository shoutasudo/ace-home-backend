<?php

namespace App\Http\Requests\Admin\Infomation;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends ApiRequest
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
        return [
            "title" => ['required'],
            "tag" => ['required'],
            "content" => ['required']
        ];
    }
}
