@props([
    'title' => null,
    'description' => null,
    'custom' => null,
    'seo' => null,
    'robots' => [],
    'titleSuffix' => null,
    'titleSuffixSeparator' => '|',

    'defaults' => [
        'title' => config('seo.title'),
        'description' => config('seo.description'),
    ],
])

@php
    if ($seo) {
        if ($seo->seo_title) {
            $title = $seo->seo_title;
        }

        if ($seo->description) {
            $description = $seo->description;
        }

        if ($seo->noindex || ! app()->environment('production')) {
            $robots[] = 'noindex';
        }

        if ($seo->nofollow || ! app()->environment('production')) {
            $robots[] = 'nofollow';
        }
    }

    $title ??= $defaults['title'];
    $titleSuffix ??= config('seo.title_suffix', null);
    $description ??= $defaults['description'];
@endphp

<title>{{ strip_tags($title) }}@if($titleSuffix) {{ "{$titleSuffixSeparator} {$titleSuffix}" }} @endif</title>
<meta name="description" content="{{ Str::limit(strip_tags($description), 170) }}">
<meta name="robots" content="{{ implode(',', $robots) }}">
{{ $custom }}
