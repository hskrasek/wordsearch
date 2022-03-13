<?php

namespace App\Models;

use App\Casts\Grid as GridCast;
use App\Game\Difficulty;
use App\Game\Direction;
use App\Game\Grid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'difficulty',
    ];

    protected $casts = [
        'difficulty' => Difficulty::class,
        'grid'       => GridCast::class,
    ];

    /**
     * @param Difficulty $difficulty
     * @param Collection<Word> $words
     *
     * @return Game
     */
    public static function start(Difficulty $difficulty, Collection $words): Game
    {
        $game             = new self();
        $game->difficulty = $difficulty;
        $game->uuid       = Str::uuid();

        $grid = new Grid($difficulty->gridSize());

        $words->each(fn (Word $word) => $grid->insertWord($word->text, Direction::random()));

        $grid->finalize();

        $game->grid = $grid;

        $game->save();
        $game->words()->attach($words);

        return $game;
    }

    public function words(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Word::class);
    }
}
