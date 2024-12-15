<?php

declare(strict_types=1);

use App\Models\Label;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_model_for_label')) {
    function get_model_for_label(string $label, ?array $load = null, bool $ignoreCache = false): mixed
    {
        if ($ignoreCache) {
            $model = Label::getModel($label);

            if ($load) {
                $model->load($load);
            }

            return $model;
        }

        return Cache::remember("label.{$label}", 60 * 60 * 24, function () use ($label, $load) {
            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = Label::getModel($label);

            if ($load) {
                $model->load($load);
            }

            return $model;
        });
    }
}
