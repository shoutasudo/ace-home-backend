<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'name' => 'required|string|max:32',
            'comment' => 'required|string|max:20',
            'role' => 'required|string|max:10',
            'image' => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は必須項目です。',
            'name.string' => '名前は文字列でなければなりません。',
            'name.max' => '名前は32文字以内で入力してください。',

            'comment.required' => 'コメントは必須項目です。',
            'comment.string' => 'コメントは文字列でなければなりません。',
            'comment.max' => 'コメントは20文字以内で入力してください。',

            'role.required' => '役割は必須項目です。',
            'role.string' => '役割は文字列でなければなりません。',
            'role.max' => '役割は10文字以内で入力してください。',

            'image.image' => '画像ファイルを選択してください。',
        ];
    }
}
