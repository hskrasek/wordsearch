<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Game\Difficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGame extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $difficulties = collect(Difficulty::cases())->map->name;

        return [
            'difficulty' => [
                'required',
                'string',
                Rule::in($difficulties),
            ],
        ];
    }

    public function difficulty(): Difficulty
    {
        return Difficulty::fromName($this->input('difficulty')) ?? Difficulty::from(
            (int) $this->input('difficulty')
        );
    }
}
