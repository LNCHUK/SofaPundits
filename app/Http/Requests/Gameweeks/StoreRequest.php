<?php

namespace App\Http\Requests\Gameweeks;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'string',
            ],
            'start_date' => [
                'required', 'date',
            ],
            'end_date' => [
                'required', 'date',
            ],
            'description' => [
                'nullable', 'string',
            ],
        ];
    }
}
