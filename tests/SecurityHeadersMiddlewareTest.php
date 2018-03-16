<?php

// use PHPUnit\Framework\TestCase;
use Orchestra\Testbench\TestCase as Orchestra;
use Sen0rxol0\SecurityHeaders\SecurityHeadersServiceProvider;
use Sen0rxol0\SecurityHeaders\SecurityHeadersMiddleware;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Cache;

final class SecurityHeadersMiddlewareTest extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
        $this->registerMiddleWare();
    }

    protected function getPackageProviders($app)
    {
        return [SecurityHeadersServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    { 
        // $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');

        $app['router']->get('/', function() {
            return 'Connection establised.';
        });

    }

    protected function registerMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(SecurityHeadersMiddleware::class);
    }

    /**
     * @covers SecurityHeadersMiddleware
     */
    public function testMiddleware()
    {
        $res = $this->get('/');

        $res->assertSuccessful();
        $this->assertEquals('Connection establised.', $res->getContent());

        $headers = $res->headers;
        $this->assertContains("default-src 'self';", $headers->get('Content-Security-Policy'));

        // $this->
    }

    public function testCanStoreNonce()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $nonces = Cache::get('script_nonces', '');
        // $this->assertEquals(strlen($nonces), 32);
        // $this->assertTrue(is_string($nonces));
        $this->assertTrue(is_array(json_decode($nonces)));
    }

}