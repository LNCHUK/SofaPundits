<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPreferencesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function prepareForValidation()
    {
        // Ensure that any checkboxes that are unchecked are added to the validation array
        foreach ($this->expected_preferences['checkbox'] as $checkboxPreferenceSlug) {
            $this->merge([$checkboxPreferenceSlug => $this->get($checkboxPreferenceSlug, '0')]);
        }
    }
}
