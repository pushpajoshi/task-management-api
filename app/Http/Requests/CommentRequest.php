<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'comment'=>'required|string',
            'attachment'=>'nullable|file|mimes:pdf,jpg,png|max:5120'
        ];
    }

    public function messages(): array
    {
        return [
            // Comment
            'comment.required' => 'Please enter a comment before submitting.',
            'comment.string' => 'The comment must be a valid text.',

            // Attachment
            'attachment.file' => 'The attachment must be a valid file.',
            'attachment.mimes' => 'The attachment must be a file of type: pdf, jpg, or png.',
            'attachment.max' => 'The attachment may not be larger than 5MB.',
        ];
    }
}
