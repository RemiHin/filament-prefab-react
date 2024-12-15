<?php

namespace App\Contracts;

interface IsSearchable
{
    /** The name of the instance, for example the title of a page */
    public function getName(): string;

    /** The route to detail in the frontend */
    public function getRoute(): string;

    /**
     * The identifying name of the resource, typically that is the singular translation of the models class name
     * For example `Pagina` for the page model or 'Nieuws' for news.
     */
    public static function getResourceName(): string;
}
