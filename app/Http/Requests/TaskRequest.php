<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class TaskRequest extends FormRequest
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

        $user = Auth::user();

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($user && $user->role !== 'admin') {
                // If normal user, allow only status update
                return [
                    'status' => 'required|in:todo,in-progress,done',
                ];
            }
        }
        $rules= [
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:todo,in-progress,done',
            'due_date'=>'required|date|date_format:Y-m-d',
            'user_id' => 'required|exists:users,id',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'The task title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'description.string' => 'The description must be a valid string.',

            'status.required' => 'Please select a task status.',
            'status.in' => 'Status must be one of: todo, in-progress, or done.',

            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.date_format' => 'The due date must be in Y-m-d format (e.g., 2025-10-07).',

            'user_id.required' => 'Please assign a user to this task.',
            'user_id.exists' => 'The selected user does not exist in our records.',
        ];
    }
}
