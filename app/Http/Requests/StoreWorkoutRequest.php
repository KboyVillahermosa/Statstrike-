<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'drills' => ['nullable', 'string', 'max:5000'],
            'rounds' => ['required', 'integer', 'min:1', 'max:999'],
            'duration' => ['required', 'integer', 'min:1', 'max:1440'], // Max 24 hours
            'rpe' => ['required', 'integer', Rule::in(range(1, 10))],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rounds.required' => 'The rounds field is required.',
            'rounds.integer' => 'The rounds must be a number.',
            'rounds.min' => 'The rounds must be at least 1.',
            'duration.required' => 'The duration field is required.',
            'duration.integer' => 'The duration must be a number.',
            'duration.min' => 'The duration must be at least 1 minute.',
            'rpe.required' => 'The RPE field is required.',
            'rpe.integer' => 'The RPE must be a number between 1 and 10.',
            'rpe.in' => 'The RPE must be between 1 and 10.',
        ];
    }
}
