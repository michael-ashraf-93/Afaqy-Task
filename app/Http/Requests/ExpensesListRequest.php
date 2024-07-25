<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpensesListRequest extends FormRequest
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
            'vehicle_id' => 'integer|exists:vehicles,id',
            'vehicle_name' => 'string|nullable',
            'plate_number' => 'string|nullable',
            'type' => 'array|nullable',
            'type.*' => 'in:fuel,insurance,service',
            'sort_by' => 'in:cost,created_at|nullable',
            'sort_direction' => 'in:asc,desc|nullable',
            'max_creation_date' => 'date|nullable|after_or_equal:min_creation_date',
            'min_creation_date' => ['date', 'nullable', function ($attribute, $value, $fail) {
                if (request('max_creation_date') && request('max_creation_date') < $value) {
                    $fail('The min creation date field must be a date before or equal to max creation date (' . $value . ')');
                }
            }],
            'min_cost' => ['numeric', 'nullable', function ($attribute, $value, $fail) {
                if (request('max_cost') && request('max_cost') < $value) {
                    $fail('Max cost must be less than or equal to ' . $value);
                }
            }],
            'max_cost' => ['numeric', 'nullable', function ($attribute, $value, $fail) {
                if (request('min_cost') && request('min_cost') > $value) {
                    $fail('Max cost must be less than or equal to ' . $value);
                }
            }],
        ];
    }
}
