<?php

namespace App\services;

class JsonResponseService
{

    /**
     * return json response with data and ok 200 http status
     *
     * @param $data
     * @return mixed
     */
    public function okResponse($data)
    {
        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }


    /**
     * return json response with data and ok 200 http status
     *
     * @param $data
     * @return mixed
     */
    public function serverErrorResponse($data)
    {
        return response(json_encode($data), 500)
            ->header('Content-Type', 'application/json');
    }


    /**
     * return json response with data and created 201 http status
     *
     * @param $data
     * @return mixed
     */
    public function createdResponse($data)
    {
        return response(json_encode($data), 201)
            ->header('Content-Type', 'application/json');
    }


    /**
     * return json response with unauthorized message and 401 http  status
     *
     * @return mixed
     */
    public function unauthorizedResponse()
    {
        return response(json_encode('message : Unauthorized action'), 401)
            ->header('Content-Type', 'application/json');
    }


    /**
     * return json response with time expired message and 401 http  status
     *
     * @return mixed
     */
    public function timeExpiredResponse()
    {
        return response(json_encode('message : Time limit to change expired'),
            401)
            ->header('Content-Type', 'application/json');
    }


    /**
     * return json response with user ban message
     *
     * @param $user
     * @return mixed
     */
    public function userBanResponse($user)
    {
        return response(json_encode("message : user $user->id was banned "), 200)
            ->header('Content-Type', 'application/json');
    }


}