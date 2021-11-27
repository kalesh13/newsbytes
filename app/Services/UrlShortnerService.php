<?php

namespace App\Services;

use Exception;
use App\Contracts\Encoder;
use App\Models\UrlMapping;
use Illuminate\Support\Str;
use App\Contracts\UrlShortner;
use Illuminate\Support\Facades\DB;

class UrlShortnerService implements UrlShortner
{
    protected $encoder;

    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Creates a new url map model for the given `$originalUrl`. If no `$user` argument is provided,
     * authenticated user is set as the url creator.
     * 
     * @param string $originalUrl
     * @param bool $accessOnlyOnce
     * @return \App\Models\UrlMapping
     */
    public function create($originalUrl, $accessOnlyOnce = false, $user = null)
    {
        return DB::transaction(function () use ($originalUrl, $accessOnlyOnce, $user) {
            $urlMapping = new UrlMapping();
            $urlMapping->original_url = $this->validateUrl($originalUrl);
            $urlMapping->access_only_once = $accessOnlyOnce;
            $urlMapping->user_id = is_null($user) ? auth()->user() : $user;

            if (!$urlMapping->save()) {
                throw new Exception('Error creating new url map for ' . $originalUrl);
            }

            $urlMapping->key = $this->getKey($urlMapping->getKey());
            $urlMapping->save();

            return $urlMapping;
        });
    }

    /**
     * Performs validation checks on the given url and returns the same.
     * 
     * @param string $url
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateUrl($url)
    {
        $url = Str::startsWith($url, 'http') ? $url : 'http://' . $url;

        validator(['url' => $url], ['url' => 'required|active_url'])->validate();

        return $url;
    }

    /**
     * Returns an encoded key for the given UrlMapping id
     * 
     * @param int $id
     * @return string
     */
    protected function getKey($id)
    {
        return $this->encoder->encode($id);
    }

    /**
     * Returns the decoded UrlMapping id from the given key.
     * 
     * @param int $id
     * @return string
     */
    protected function getId($key)
    {
        return $this->encoder->decode($key);
    }

    /**
     * Get UrlMapping of the given key.
     * 
     * @param string $key
     * @return \App\Models\UrlMapping|null
     */
    public function getUrlMappingOfKey($key)
    {
        return UrlMapping::query()->find($this->getId($key));
    }

    /**
     * Increments the open stat of the given mapping and returns the current open count.
     * 
     * @param \App\Models\UrlMapping $urlMapping
     * @return int
     */
    public function updateOpenStat($urlMapping)
    {
        $urlMapping->increment('opens');

        return $urlMapping->opens;
    }

    /**
     * If a url was already opened once and has a access_only_once property set to true, we'll 
     * disallow access to the same.
     * 
     * @param \App\Models\UrlMapping $urlMapping
     * @return boolean
     */
    public function canBeAccessed($urlMapping)
    {
        if (null == $urlMapping) {
            return false;
        }
        return !$urlMapping->access_only_once || $urlMapping->opens == 0;
    }
}
