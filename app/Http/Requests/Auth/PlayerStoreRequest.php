<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PlayerStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'position' => 'string|max:10|nullable',
            'height' => 'string|max:10|nullable',
            'weight' => 'integer|nullable',
            'jersey_number' => 'string|max:5|nullable',
            'college' => 'string|max:150|nullable',
            'country' => 'required|string|max:100',
            'draft_year' => 'integer|nullable',
            'draft_round' => 'integer|nullable',
            'draft_number' => 'integer|nullable',
            'team_full_name' => 'required|string|max:100|exists:teams,full_name',
        ];
    }
}
