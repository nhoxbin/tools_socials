<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|between:3,50',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:3'
        ];
    }

    public function messages() {
        return [
            'required' => 'Bạn chưa nhập :attribute.',
            'between' => ':Attribute phải từ :min - :max kí tự!',
            'min' => ':Attribute phải lớn hơn :min kí tự',
            'email.email' => 'Email không đúng định dạng!',
            'email.unique' => 'Email này đã được đăng kí!',
            'password.confirmed' => 'Password không giống nhau.',
        ];
    }
}
