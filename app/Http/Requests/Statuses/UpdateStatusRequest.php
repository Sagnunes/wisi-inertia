<?php

namespace App\Http\Requests\Statuses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
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
        $status = $this->route('status');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('statuses')->ignore($status->id)],
            'description' => 'max:255',
        ];
    }
}
