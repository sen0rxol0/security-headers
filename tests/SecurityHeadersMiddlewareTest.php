<?php

// use PHPUnit\Framework\TestCase;
use Orchestra\Testbench\TestCase as Orchestra;
use Sen0rxol0\SecurityHeaders\SecurityHeadersServiceProvider;
use Sen0rxol0\SecurityHeaders\SecurityHeadersMiddleware;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;
// use Illuminate\Routing\RouteCollection;
// use Route;

final class SecurityHeadersMiddlewareTest extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
        $this->registerMiddleWare();
        // $this->setUpRoutes($this->app);
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
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');

        $app['router']->get('/', function() {
            return 'Connection establised.';
        });

    }

    protected function registerMiddleware()
    {
        $this->app->make(Kernel::class)->pushMiddleware(SecurityHeadersMiddleware::class);
    }

    // protected function setUpRoutes($app)
    // {
    //     $app['router']->setRoutes(new RouteCollection());

    //     Route::get('/', function(Request $request) {
    //         return response('Connection establised.', 200);
    //     });
    // }

    public function testMiddleware()
    {
        $res = $this->get('/');

        // $res = $this->call('GET', '/');
        $this->assertEquals('Connection establised.', $res->getContent());
        
        // $response->assertStatus(200);
        // $this->assertEquals('Connection establised.', $response->getContent());
    }

}