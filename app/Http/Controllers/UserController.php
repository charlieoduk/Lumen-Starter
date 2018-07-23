<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Gets all users from the database
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        $users = User::all();
        if (empty($users)) {
            return $this->error("There are no users", 404);
        }
        return $this->success($users, 200);
    }

    /**
     * Creates a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $this->validate($request, User::$rules);
        } catch (ValidationException $e) {
        }

       $user = new User();

       $user->email = $request->get('email');
       $user->name = $request->get('name');
       $user->password = Hash::make($request->get('password'));

       $user->save();

        return $this->success("The user with id {$user->id} has been created", 201);
    }

    /**
     * Get a single user with the given id
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser( $user_id)
    {

        $user = User::find($user_id);
        if (!$user) {
            return $this->error("The user with id {$user_id} doesn't exist", 404);
        }
        return $this->success($user, 200);
    }

    /**
     * Update user with id given
     *
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return $this->error("The user with id {$user_id} doesn't exist", 404);
        }


        try {
            $this->validate($request, User::$rules);
        } catch (ValidationException $e) {
        }


        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return $this->success("The user with with id {$user->id} has been updated", 200);
    }

    /**
     * Delete a user with the given id
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($user_id)
    {
        $user = User::find($user_id);
        if(!$user){
            return $this->error("The user with {$user_id} doesn't exist", 404);
        }
        $user->delete();
        return $this->success("The user with with id {$user_id} has been deleted", 200);
    }
}
