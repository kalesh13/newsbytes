<?php

namespace Tests\Unit;

use App\Contracts\UrlShortner;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UrlMappingServiceTest extends TestCase
{
    /**
     * Url Shortner service
     * 
     * @var \App\Contracts\UrlShortner
     */
    protected $urlShortner;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->urlShortner = $this->app->make(UrlShortner::class);
    }

    /**
     * Test whether url shortner service is able to create a tiny url
     * 
     * @return void
     */
    public function testUrlShortnerServiceCreatesUrlMapping()
    {
        $urlMapping = $this->urlShortner->create('https://www.google.com');

        $this->assertNotNull($urlMapping->key);
        $this->assertDatabaseHas('url_mappings', ['id' => $urlMapping->getKey()]);
    }

    /**
     * Invalid urls should throw validation exception
     * 
     * @return void
     */
    public function testUrlShortnerServiceThrowsValidationErrorForInvalidUrl()
    {
        $this->expectException(ValidationException::class);

        $this->urlShortner->create('abc');
    }

    /**
     * Http protocol is automatically added to urls without any protocol. This is required
     * or redirect url will be relative to the application server url.
     * 
     * @return void
     */
    public function testUrlShortnerServiceAppendsProtocolsToOriginal()
    {
        $urlMapping = $this->urlShortner->create('google.com');

        $this->assertStringStartsWith('http', $urlMapping->original_url);
        $this->assertEquals('http://google.com', $urlMapping->original_url);
    }

    /**
     * Check whether service is able to encode and decode properly ie, service is able to create
     * key for a mapping and can retrieve the original url from key.
     * 
     * @return void
     */
    public function testUrlShortnerServiceEncodingAndDecodingWorks()
    {
        $urlMapping = $this->urlShortner->create('google.com');
        $this->assertNotNull($key = $urlMapping->key);

        $decodedMapping = $this->urlShortner->getUrlMappingOfKey($key);

        $this->assertEquals($urlMapping->getKey(), $decodedMapping->getKey());
        $this->assertEquals($urlMapping->original_url, $decodedMapping->original_url);
    }
}
