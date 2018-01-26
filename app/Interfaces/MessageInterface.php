<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Message;

interface MessageInterface
{

    public function read();


    public function create(Request $request);


    public function delete(Message $Message);


    public function update(Request $request, Message $Message);


    public function ban(Message $message, Request $request);
}