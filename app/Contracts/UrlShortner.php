<?php

namespace App\Contracts;

interface UrlShortner
{
    /**
     * Creates a new url map model for the given `$originalUrl`. If no `$user` argument is provided,
     * authenticated user is set as the url creator.
     * 
     * @param string $originalUrl
     * @param boolean $accessOnlyOnce
     * @return \App\Models\UrlMapping
     */
    public function create($originalUrl, $accessOnlyOnce = false, $user = null);

    /**
     * Get UrlMapping of the given key.
     * 
     * @param string $key
     * @return \App\Models\UrlMapping|null
     */
    public function getUrlMappingOfKey($key);

    /**
     * Increments the open stat of the given mapping and returns the current open count.
     * 
     * @param \App\Models\UrlMapping $urlMapping
     * @return int
     */
    public function updateOpenStat($urlMapping);

    /**
     * If a url was already opened once and has a single_use property set, we'll disallow access to
     * the same.
     * 
     * @param \App\Models\UrlMapping $urlMapping
     * @return boolean
     */
    public function canBeAccessed($urlMapping);
}
