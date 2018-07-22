<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class AuthTest
 *
 * @category Tests
 * @package  Tests\App\HTTP\Controllers
 */
class AuthControllerTest extends TestCase
{
     /**
     * Test successful authentication
     */
    public function testAuthenticationSuccess()
    {
        $response = $this->post('api/v1/auth/login', [
            'email'=>'oduk@andela.com', 'password'=>'password'
        ]);

        $response->assertResponseStatus(200);
        $response->seeJsonContains(['name'=>'Charles Oduk']);

        $response = json_decode($this->response->getContent());
        $this->assertObjectHasAttribute('token', $response);
    }

    /**
     * Test authentication fails when credentials are incorrect
     */
    public function testAuthenticationFailsWithWrongEmail()
    {
        $response = $this->post('api/v1/auth/login', [
            'email'=>'unknown@andela.com', 'password'=>'password'
            ]
        );
        $response->seeJsonContains(['error' => 'User not found.']);
        $response->assertResponseStatus(404);
    }

    /**
     * Test authentication fails when credentials are incorrect
     */
    public function testAuthenticationFailsWithWrongPassword()
    {
        $response = $this->post('api/v1/auth/login', [
            'email'=>'oduk@andela.com', 'password'=>'wrongpassword'
            ]
        );
        $response->seeJsonContains(['error' => 'Email or password is wrong.']);
        $response->assertResponseStatus(400);
    }
}
