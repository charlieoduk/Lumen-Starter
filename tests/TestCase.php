<?php

use Illuminate\Support\Facades\Artisan;
use \Laravel\Lumen\Testing\DatabaseMigrations;
use Symfony\Component\Console\Application;

use Test\Mocks\JwtMiddlewareMock;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    use DatabaseMigrations;
     /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
 
    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');

        $jwtMiddlewareMock = new JwtMiddlewareMock();

        $this->app->instance('App\Http\Middleware\JwtMiddleware', $jwtMiddlewareMock);
    }

}
