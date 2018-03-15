<?php

// use PHPUnit\Framework\TestCase;
use Orchestra\Testbench\TestCase as Orchestra;
use Sen0rxol0\SecurityHeaders\SecurityHeadersServiceProvider;
use Sen0rxol0\SecurityHeaders\SecurityHeadersMiddleware;
// use Illuminate\Support\Facades\Request;

final class SecurityHeadersMiddlewareTest extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [SecurityHeadersServiceProvider::class];
    }

    // public function testMiddleware()
    // {
    //     // Create request
    //     $request = Request::create('http://example.com/', 'GET');

    //     // Pass it to the middleware
    //     // $middleware = new SecurityHeadersMiddleware();

    //     // $response = $middleware->handle($request, function () {});
    //     $this->assertEquals($response->getStatusCode(), 200);
    // }

}