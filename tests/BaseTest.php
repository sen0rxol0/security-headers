<?php

use Orchestra\Testbench\TestCase as Orchestra;
use Sen0rxol0\SecurityHeaders\SecurityHeadersServiceProvider;
use Sen0rxol0\SecurityHeaders\SecurityHeadersMiddleware;

final class BaseTest extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [SecurityHeadersServiceProvider::class];
    }

    // public function testCanRetreiveNonce()
    // {
    //     $response = $this->get('/');

    //     // $response->assertStatus(200);

    //     // $nonce = nonce('script-src');

    //     // $this->assertTrue(is_string($nonce));
    // }

}