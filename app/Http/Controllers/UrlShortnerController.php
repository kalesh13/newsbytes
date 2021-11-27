<?php

namespace App\Http\Controllers;

use App\Models\UrlMapping;
use Illuminate\Http\Request;
use App\Contracts\UrlShortner;

class UrlShortnerController extends Controller
{
    /**
     * Returns the url shortner landing page
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Creates a new shortened url
     * 
     * @return \App\Models\UrlMapping
     */
    public function create(Request $request, UrlShortner $urlShortner)
    {
        return $urlShortner->create($request->original_url, $request->access_only_once ?? false);
    }

    /**
     * Redirects the tiny url to the original page. Also updates the open count of
     * the url.
     * 
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function redirect(UrlShortner $urlShortner, $key)
    {
        $mapping = $urlShortner->getUrlMappingOfKey($key);

        if (null == $mapping || !$urlShortner->canBeAccessed($mapping)) {
            return abort(404);
        }
        $urlShortner->updateOpenStat($mapping);

        return redirect($mapping->original_url);
    }

    /**
     * Retrieves the most recent ten url mappings
     * 
     * @return array
     */
    public function retrieve(Request $request)
    {
        $query = UrlMapping::query()->latest('id')->take(10);

        if (is_null($search = $request->input('search'))) {
            $query->whereNotNull('key');
        } else {
            $query->where('original_url', $search);
        }

        return $query->get();
    }
}
