<?php

namespace App\Interfaces;

use App\CrudClasses\Message\MessageCreator;
use App\CrudClasses\Message\MessageDeleter;
use App\CrudClasses\Message\MessageReader;
use App\CrudClasses\Message\MessageUpdater;
use App\Repositories\UserDataRepository;
use App\Services\BanService;
use Illuminate\Http\Request;
use App\Message;

interface MessageInterface
{

    public function read(MessageReader $messageReader);


    public function create(MessageCreator $messageCreator,
                           Request $request);


    public function delete(MessageDeleter $messageDeleter,
                           Message $Message);


    public function update(MessageUpdater $messageUpdater,
                           Request $request,
                           Message $Message);


    public function ban(BanService $banService,
                        UserDataRepository $dataRepository,
                        Message $message,
                        Request $request);
}