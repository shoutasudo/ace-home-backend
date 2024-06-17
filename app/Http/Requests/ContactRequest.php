<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ContactRequest extends ApiRequest
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
            'contactType' => ['required'],
            'name' => ['required', 'max:255'],
            'companyName' => ['nullable', 'max:255'],
            'telNumber' => ['required', 'regex:/^[0-9]+$/', 'digits_between:10,11'],
            'email' => ['required', 'email', 'max:255'],
            'content' => ['required', 'max:1000']
        ];
    }

    public function messages(): array
    {
        return [
            'contactType.required' => "お問い合わせ内容を選択してください",
            'name.required' => "お名前を入力してください",
            'name.max' => "お名前は255文字以内で入力してください",
            'companyName.max' => "会社名は255文字以内で入力してください",
            'telNumber.required' => '電話番号を入力してください',
            'telNumber.regex' => '電話番号は数字のみで記載してください（ハイフンは不要です）',
            'telNumber.digits_between' => '電話番号は10～11桁にて入力してください' ,
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式にて入力してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'content.required' => '内容本文を入力してください',
            'content.max' => '内容本文は1000字以内で入力してください。',
        ];
    }
}
