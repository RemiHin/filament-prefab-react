<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use SolutionForest\FilamentTree\Concern\ModelTree;

class MenuItem extends Model
{
    use ModelTree;

    protected $guarded = [];

    protected $appends = [
        'route',
        'is_internal',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function menuable(): MorphTo
    {
        return $this->morphTo();
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function getRouteAttribute(): string
    {
        return $this->getUrl();
    }

    public function getIsInternalAttribute(): bool
    {
        return $this->url_type === 'internal';
    }

    public function getUrl(): ?string
    {
        if ($this->url_type === 'internal') {
            return $this->menuable?->getRoute();
        }

        return $this->url;
    }
}
