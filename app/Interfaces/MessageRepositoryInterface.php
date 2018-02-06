<?php

namespace App\Interfaces;


interface MessageRepositoryInterface
{
    public function createMessage($request);

    public function getAllMessages();

    public function updateMessage($request, $message);

    public function getOldRecords();

    public function getOldestRecord();
}