<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $words = LazyCollection::make($this->container->make('words'));
        // $words->keys()->each(fn(string $word) => Word::create(['text' => Str::upper($word)]));
        Word::create(
            [
                'text' => 'APPLE'
            ]
        );

        Word::create(
            [
                'text' => 'HALO'
            ]
        );

        Word::create(
            [
                'text' => 'DESTINY'
            ]
        );

        Word::create(
            [
                'text' => 'GUARDIAN'
            ]
        );

        Word::create(
            [
                'text' => 'WITNESS'
            ]
        );

        Word::create(
            [
                'text' => 'LIGHT'
            ]
        );

        Word::create(
            [
                'text' => 'DARKNESS'
            ]
        );

        Word::create(
            [
                'text' => 'SCORN'
            ]
        );

        Word::create(
            [
                'text' => 'HIVE'
            ]
        );

        Word::create(
            [
                'text' => 'LOVE'
            ]
        );

        Word::create(
            [
                'text' => 'ENTER'
            ]
        );

        Word::create(
            [
                'text' => 'GIVE'
            ]
        );

        Word::create(
            [
                'text' => 'COMMUNE'
            ]
        );

        Word::create(
            [
                'text' => 'KILL'
            ]
        );

        Word::create(
            [
                'text' => 'TRAVELLER'
            ]
        );

        Word::create(
            [
                'text' => 'PYRAMID'
            ]
        );

        Word::create(
            [
                'text' => 'ASCENDANT'
            ]
        );
    }
}
