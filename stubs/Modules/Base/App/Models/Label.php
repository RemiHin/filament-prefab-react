<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Label extends Model
{
    protected $guarded = [];

    public static function getModel(string $label, ?string $model = null): ?Model
    {
        $query = static::where('label', $label);

        if (! empty($model)) {
            $query->where('labelable_type', $model);
        }

        return optional($query->first())->model;
    }

    public function model(): MorphTo
    {
        return $this->morphTo('labelable');
    }
}
