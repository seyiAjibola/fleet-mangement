<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait OwnedByUser
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('created_by_user_id', $user->id);
    }

    public function isVisibleTo(?User $user): bool
    {
        return (bool) ($user && ($user->isAdmin() || (int) $this->created_by_user_id === (int) $user->id));
    }
}
