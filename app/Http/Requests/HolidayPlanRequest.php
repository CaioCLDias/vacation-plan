<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HolidayPlanRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'participants' => 'nulablel|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'date.required' => 'The date field is required.',
            'location.required' => 'The location field is required.',
            'title.string' => 'The title must be a string.',
            'description.string' => 'The description must be a string.',
            'date.date' => 'The date must be a date.',
            'location.string' => 'The location must be a string.',
            'participants.array' => 'The participants must be an array.',
        ];
    }
}
