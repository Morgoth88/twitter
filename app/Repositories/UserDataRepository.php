<?php

namespace App\Repositories;

use App\Message;
use App\Comment;
use App\User;

class UserDataRepository
{


    /**
     * return misc user data
     *
     * @param $user
     * @return mixed
     */
    public function getUserData($user)
    {
        $result['MessagesCount'] = Message::where(
            [['user_id', $user->id], ['old', 0]])
            ->count();

        $result['CommentsCount'] = Comment::where(
            [['user_id', $user->id], ['old', 0]]
        )->count();

        $result['lastCreatedMessageTime'] = Message::where(
            [['user_id', $user->id], ['old', 0]])
            ->max('created_at');

        $result['lastCreatedCommentTime'] = Comment::where(
            [['user_id', $user->id], ['old', 0]])
            ->max('created_at');

        $result['user'] = $user;

        return $result;
    }


    /**
     * return user by id
     *
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return User::find($id);
    }


    /**
     * update user's account
     *
     * @param $user
     * @param $request
     */
    public function updateUserData($user, $request)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->new_password);
        $user->save();
    }


    /**
     * ban user
     *
     * @param $user
     */
    public function banUser($user)
    {
        $user->ban = 1;
        $user->save();
    }


    /**
     * create new user
     *
     * @param $data
     * @return mixed
     */
    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}
