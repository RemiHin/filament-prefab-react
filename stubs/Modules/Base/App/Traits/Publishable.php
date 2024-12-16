<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function getPublishFromKey(): ?string
    {
        return 'publish_from';
    }

    public function getPublishFromFallback(): ?string
    {
        return 'created_at';
    }

    public function getPublishUntilKey(): string
    {
        return 'publish_until';
    }

    public function scopeOrderByPublishedAt(Builder $query, string $direction = 'DESC')
    {
        $query->orderByRaw("IFNULL({$this->getPublishFromKey()}, {$this->getPublishFromFallback()}) {$direction}");
    }

    public function scopePublished(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->where($this->getPublishFromKey(), '<=', Carbon::now())
                ->orWhereNull($this->getPublishFromKey());
        })->where(function (Builder $query) {
            $query->where($this->getPublishUntilKey(), '>=', Carbon::now())
                ->orWhereNull($this->getPublishUntilKey());
        });
    }

    public function scopeUnpublished(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->orWhere(function (Builder $subQuery) {
                $subQuery->whereNotNull($this->getPublishFromKey())
                    ->where($this->getPublishFromKey(), '>', Carbon::now());
            })->orWhere(function (Builder $subQuery) {
                $subQuery->whereNotNull($this->getPublishUntilKey())
                    ->where($this->getPublishUntilKey(), '<', Carbon::now());
            });
        });
    }

    public function isPublished(): bool
    {
        /** @var Carbon|null $publishFrom */
        $publishFrom = $this->getAttribute($this->getPublishFromKey());
        /** @var Carbon|null $publishUntil */
        $publishUntil = $this->getAttribute($this->getPublishUntilKey());

        if ($publishFrom === null && $publishUntil === null) {
            return true;
        }

        if (is_null($publishUntil)) {
            return Carbon::now()->gte($publishFrom);
        }

        if (is_null($publishFrom)) {
            return $publishUntil->gte(Carbon::now());
        }

        return Carbon::now()->isBetween($publishFrom, $publishUntil);
    }
}
