@props([
    'title' => '',
    'description' => '',
    'image' => null,
    'seo' => null,
    'url' =>  url()->current(),
    'type' => 'website',
])

@php
    if(!$title && $seo) {
        $title = $seo->og_title ?? $seo->seo_title;
    }

    if(!$description && $seo && $seo->description) {
        $description = $seo->description;
    }

    if (!$image && $seo && $seo->image?->getSignedUrl()) {
        $image = asset($seo->image?->getSignedUrl());
    } else if (!$image && config('seo.og_image')) {
        $image = asset(config('seo.og_image'));
    }

    if(empty($title)) {
        $title = config('seo.og_title');
    }
@endphp

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ strip_tags($title) }}"/>
<meta property="og:description" content="{{ Str::limit(strip_tags($description), 200) }}"/>
<meta property="og:url" content="{{ $url }}"/>
@if($image)
    <meta property="og:image" content="{{ $image }}"/>
@endif
