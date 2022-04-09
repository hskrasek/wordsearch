<?php

namespace App\Http\Requests;

use App\Models\Game;
use App\Models\Word;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SolveGame extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        /** @var Game $game */
        $game = $this->route('game');

        return [
            'word' => [
                'required',
                'string',
                'exists:' . Word::class . ',text',
                Rule::in($game->words->map->text->toArray()),
            ],
            'coordinates' => [
                'required',
                'array'
            ],
            'coordinates.*.0' => [
                'required',
                'integer',
                'min:0',
                'max:' . $game->grid->size()
            ],
            'coordinates.*.1' => [
                'required',
                'integer',
                'min:0',
                'max:' . $game->grid->size()
            ]
        ];
    }

    public function word(): Word
    {
         return Word::where(['text' => $this->input('word')])->first();
    }

    /**
     * @return array<int, <array{0: int, 1: int}>>
     */
    public function coordinates(): array
    {
        return $this->input('coordinates');
    }
}
