<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RegisterRequest extends FormRequest
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
            'name'=>'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->whereNull('deleted_at'), // only unique among non-deleted users
            ],
            'password' => 'required|string|min:6|same:confirm_password', // matches confirm_password field
            'confirm_password' => 'required|string|min:6', 
            'role'=> ['nullable','in:admin,user'],       
        ];
    }

    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'Please enter the user\'s name.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name may not exceed 255 characters.',

            // Email
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',

            // Password
            'password.required' => 'A password is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.same' => 'The password and confirmation password must match.',

            // Confirm Password
            'confirm_password.required' => 'Please confirm your password.',
            'confirm_password.string' => 'The confirmation password must be a valid string.',
            'confirm_password.min' => 'The confirmation password must be at least 6 characters long.',

            // Role
            'role.in' => 'The selected role must be either admin or user.',
        ];
    }
}
