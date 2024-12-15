<?php

namespace App\Models;

use App\Contracts\Menuable;
use App\Traits\HasVisibility;
use App\Traits\Seoable;
use App\Traits\Labelable;
use App\Traits\Searchable;
use App\Contracts\IsSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model implements Menuable, IsSearchable
{
    use HasFactory;
    use Labelable;
    use Seoable;
    use HasVisibility;

    protected $guarded = [];

    protected $casts = [
        'content' => 'array',
        'visible' => 'bool',
    ];

    public function getUrlAttribute(): string
    {
        return route('page.show', ['page' => $this]);
    }

    public static function getMenuOptions(): array
    {
        return self::query()->pluck('name', 'id')->toArray();
    }

    public static function getResourceName(): string
    {
        return __('Page');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoute(): string
    {
        return route('page.show', ['page' => $this]);
    }
}
