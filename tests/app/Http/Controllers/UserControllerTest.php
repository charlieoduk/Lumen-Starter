<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class UserControllerTest
 *
 * @category Tests
 * @package  Tests\App\HTTP\Controllers
 */
class UserControllerTest extends TestCase
{
    /**
     * Test unable to get users if not logged in
     */
    public function testGetUsersWithoutLoggingIn()
    {
        $response = $this->get('api/v1/users');

        $response->assertResponseStatus(401);
        $response->seeJsonContains(['error' => 'Authentication Token not provided.']);

    }

    /**
     * Test get users
     */
    public function testGetUsers()
    {
        $token = 'awesometoken';

        $response = $this->get('api/v1/users', ['HTTP_Authorization' => $token]);
        
        $this->assertResponseStatus(200);

        $response = json_decode($this->response->getContent());

        $this->assertNotEmpty($response);

        $this->assertAttributeContains("Charles Oduk", "name", $response->data[0]);

        $this->assertAttributeContains("Dominic Bett", "name", $response->data[1]);

        $this->assertAttributeContains("Madge Kinyanjui", "name", $response->data[2]);

        $this->assertCount(3, $response->data);
    }

    /**
     * Test get a user
     */
    public function testGetUser(){

        $token = 'workingtoken';

        $this->get('api/v1/users/1', ['HTTP_Authorization' => $token]);

        $this->assertResponseStatus(200);

        $response = $this->response->getContent();

        $this->assertNotEmpty($response);

        $this->assertContains("id", $response);

        $this->assertContains("email", $response);
    }

    /**
     * Test get a user
     */
    public function testRegisterUser() {

        $this->artisan('migrate:refresh');

        $response = $this->post('api/v1/users/register', [
            'name' => 'Human Person',
            'email' => 'hp@gmail.com',
            'password' => 'password'
        ]);

        $response->assertResponseStatus(201);
    }

    /**
     * Test get update a user
     */
    public function testUpdateUser() {

        $response = $this->put('api/v1/users/1',[
            'name' => 'Human Person',
            'email' => 'hddp@gmail.com',
            'password' => 'password'
        ], ['HTTP_Authorization' => 'workingtoken']);

        $response->assertResponseStatus(200);
        $response->seeJsonContains(['data' => 'The user with with id 1 has been updated']);
    }

    /**
     * Test delete a user
     */
    public function testDeleteUserSuccess() {

        $response = $this->delete('api/v1/users/1',[], ['HTTP_Authorization' => 'workingtoken']);

        $response->assertResponseStatus(200);
        $response->seeJsonContains(['data' => 'The user with with id 1 has been deleted']);
    }

    /**
     * Test delete a user without authorization
     */
    public function testDeleteUserWithoutAccess() {

        $response = $this->delete('api/v1/users/1');

        $response->assertResponseStatus(401);
        $response->seeJsonContains(['error' => 'Authentication Token not provided.']);
    }

    /**
     * Test delete a non existant user
     */
    public function testDeleteNonExistingUser() {

        $response = $this->delete('api/v1/users/5' ,[], ['HTTP_Authorization' => 'workingtoken']);

        $response->assertResponseStatus(404);
        $response->seeJsonContains(['message' => 'The user with 5 doesn\'t exist']);
    }
}
