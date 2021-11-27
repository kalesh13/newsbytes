<?php

namespace Tests\Feature;

use Tests\TestCase;

class UrlMappingApiTest extends TestCase
{
    /**
     * Test whether url mapping can be created from api
     * 
     * @return void
     */
    public function testTinyUrlCanBeCreatedUsingApi()
    {
        $response = $this->post('/api/tiny-url', ['original_url' => 'https://google.com']);

        $response->assertStatus(201);

        $data = $response->json();

        $this->assertEquals('https://google.com', $data['original_url']);
        $this->assertTrue(array_key_exists('tiny_url', $data));
    }

    /**
     * A restricted url should be allowed access only once.
     * 
     * @return void
     */
    public function testRestrictedUrlsCanBeAccessedOnlyOnce()
    {
        $response = $this->post('/api/tiny-url', [
            'original_url' => 'https://google.com',
            'access_only_once' => true
        ]);

        $response->assertStatus(201);

        $data = $response->json();

        // Accessing tiny url causes a redirect response to the original url. 
        $response = $this->get($data['tiny_url']);
        $response->assertStatus(302);

        // Accessing the same url second time causes a 404 page not found response.
        $response = $this->get($data['tiny_url']);
        $response->assertStatus(404);
    }

    /**
     * An unrestricted url can be accessed many times.
     * 
     * @return void
     */
    public function testUnrestrictedUrlsCanBeAccessedManyTimes()
    {
        $response = $this->post('/api/tiny-url', [
            'original_url' => 'https://google.com',
            'access_only_once' => false
        ]);

        $response->assertStatus(201);

        $data = $response->json();

        // Accessing tiny url causes a redirect response to the original url. 
        $response = $this->get($data['tiny_url']);
        $response->assertStatus(302);

        // Accessing tiny url again should cause a redirect response to the original url. 
        $response = $this->get($data['tiny_url']);
        $response->assertStatus(302);
    }

    /**
     * All url mappings created are unrestricted by default.
     * 
     * @return void
     */
    public function testUrlMappingsAreCreatedWithUnrestrictedAccessByDefault()
    {
        $response = $this->post('/api/tiny-url', ['original_url' => 'https://google.com']);

        $response->assertStatus(201);

        $data = $response->json();

        $this->assertEquals('https://google.com', $data['original_url']);
        $this->assertEquals(false, $data['access_only_once']);
    }
}
