<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Square\Hyrule\Hyrule;
use CountryState;

class EditUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $builder = Hyrule::create();

        $builder->string('birthday')
            ->sometimes()
            ->required()
            ->rule('date')
            ->lte(now()->toDateString())
            ->end();

        $builder->string('country')
            ->sometimes()
            ->requiredWith('state')
            ->in(array_keys(CountryState::getCountries()))
            ->end();

        $builder->string('state')
            ->sometimes()
            ->requiredWith('country')
            ->in(array_keys(CountryState::getStates($this->country)))
            ->end();

        return $builder->build();
    }
}
