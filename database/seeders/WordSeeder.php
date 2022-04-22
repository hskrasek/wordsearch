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
                'text' => 'DESTINY',
            ]
        );

        Word::create(
            [
                'text' => 'DARKNESS',
            ]
        );

        Word::create(
            [
                'text' => 'SCORN',
            ]
        );

        Word::create(
            [
                'text' => 'HIVE',
            ]
        );

        Word::create(
            [
                'text' => 'WITNESS',
            ]
        );

        Word::create(
            [
                'text' => 'GUARDIAN',
            ]
        );

        Word::create(
            [
                'text' => 'SAVATHUN'
            ]
        );

        Word::create(
            [
                'text' => 'BLACK'
            ]
        );

        Word::create(
            [
                'text' => 'GARDEN'
            ]
        );

        Word::create(
            [
                'text' => 'TOWER'
            ]
        );

        Word::create(
            [
                'text' => 'EARTH'
            ]
        );

        Word::create(
            [
                'text' => 'ASCENDANT',
            ]
        );

        Word::create(
            [
                'text' => 'PLANE',
            ]
        );

        Word::create(
            [
                'text' => 'FLEET',
            ]
        );

        Word::create(
            [
                'text' => 'PYRAMID',
            ]
        );


        Word::create(
            [
                'text' => 'TRAVELLER',
            ]
        );


        Word::create(
            [
                'text' => 'WORM',
            ]
        );


        Word::create(
            [
                'text' => 'HEART',
            ]
        );

        Word::create(
            [
                'text' => 'LIGHT',
            ]
        );


        Word::create(
            [
                'text' => 'BLANK',
            ]
        );


        Word::create(
            [
                'text' => 'WORSHIP',
            ]
        );


        Word::create(
            [
                'text' => 'DRINK',
            ]
        );

        Word::create(
            [
                'text' => 'STOP',
            ]
        );

        Word::create(
            [
                'text' => 'COMMUNE',
            ]
        );

        Word::create(
            [
                'text' => 'LOVE',
            ]
        );

        Word::create(
            [
                'text' => 'GRIEVE',
            ]
        );

        Word::create(
            [
                'text' => 'REMEMBER',
            ]
        );

        Word::create(
            [
                'text' => 'KILL',
            ]
        );

        Word::create(
            [
                'text' => 'GIVE',
            ]
        );

        Word::create(
            [
                'text' => 'ENTER',
            ]
        );
    }
}
