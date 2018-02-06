<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SessionDataRepository
{

    /**
     * delete user session from db by user id
     *
     * @param $user
     */
    public function deleteUserSession($user)
    {
        DB::table('sessions')->where('user_id', $user->id)->delete();
    }

}