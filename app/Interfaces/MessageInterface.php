<?php
/**
 * Created by PhpStorm.
 * User: bartos
 * Date: 20.12.2017
 * Time: 12:07
 */

namespace  App\Interfaces;

use Illuminate\Http\Request;
use App\Message;


interface MessageInterface
{
     public function read();
     public function create(Request $request);
     public function delete(Request $request, Message $Message);
     public function update(Request $request, Message $Message);
}