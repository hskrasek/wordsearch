<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

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
        if (Word::count() > 0) {
            $this->command->alert('Words table has data, please truncate table before proceeding');

            return;
        }

        $now = now();

        LazyCollection::make(function () {
            $handle = Storage::readStream('unigram_freq.csv');

            while ($line = fgetcsv($handle)) {
                yield $line;
            }
        })->chunk(10_000)->each(function (LazyCollection $lines, int $chunkNumber) use ($now) {
            $chunkNumber++;
            $words = [];
            foreach ($lines as $line) {
                if ($line[1] === 'count') {
                    continue;
                }

                $words[] = [
                    'text'       => \Str::upper($line[0]),
                    'length'     => \Str::length($line[0]),
                    'frequency'  => $line[1],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Word::insert($words);
            $this->command->info(
                "Memory usage after {$lines->count()} words in chunk #$chunkNumber: " . number_format(
                    memory_get_peak_usage() / 1048576,
                    2
                ) . ' MB'
            );
        });

        $this->command->info('Final memory usage: ' . number_format(memory_get_peak_usage() / 1048576, 2) . ' MB');
    }
}
