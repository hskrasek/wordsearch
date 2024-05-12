<?php

namespace App\Http\Resources;

use App\Game\Grid;
use App\Models\Word;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Grid $grid */
        $grid = $this->grid;

        $wordCoordinates = $grid->getWordCoordinates();

        $coordinatesToStamp = $this->words
            ->filter(fn (Word $word) => $word->session->found)
            ->reduce(
                fn (array $carry, Word $word) => [...$carry, ...$wordCoordinates[$word->text]],
                []
            );

        foreach ($coordinatesToStamp as $coords) {
            $grid[$coords[0]][$coords[1]]->find();
        }

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'difficulty' => $this->difficulty->name,
            'is_completed' => $this->isCompleted,
            'grid' => $grid->toArray(),
            'words' => WordResource::collection($this->words->sortBy(fn (Word $word) => $word->text)->values())
                ->toArray($request),
            'created_at' => $this->created_at,
            'created_date' => $this->created_at->toDateString(),
        ];
    }
}
