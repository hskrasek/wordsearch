<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LoginToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $consumed_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereConsumedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginToken whereUserId($value)
 *
 * @mixin \Eloquent
 */
class LoginToken extends Model
{
    use HasFactory;
    use Prunable;

    protected $guarded = [];

    protected $dates = [
        'expires_at',
        'consumed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function consume(): void
    {
        $this->consumed_at = now();
        $this->save();
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isConsumed();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isBefore(now());
    }

    public function isConsumed(): bool
    {
        return $this->consumed_at !== null;
    }

    public function prunable()
    {
        return static::where('expires_at', '<=', now()->subMonth());
    }
}
