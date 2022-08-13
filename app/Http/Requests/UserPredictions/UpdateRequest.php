<?php

namespace App\Http\Requests\UserPredictions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fixtures' => [
                'required',
                'array',
            ],
            'fixtures.*.home_score' => [
                'required_with:fixtures.*.away_score',
                'nullable',
                'integer',
                'min:0',
            ],
            'fixtures.*.away_score' => [
                'required_with:fixtures.*.home_score',
                'nullable',
                'integer',
                'min:0',
            ]
        ];
    }

    public function messages()
    {
        return [
            'fixtures.*.home_score.required_with' => "It looks like you forgot to set the home team score",
            'fixtures.*.away_score.required_with' => "It looks like you forgot to set the away team score",
            'fixtures.*.*.integer' => "That's an odd number of goals...",
            'fixtures.*.*.min' => "You need to predict at least 0 goals"
        ];
    }
}
