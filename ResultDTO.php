<?php


namespace NW\WebService\References\Operations\Notification;

class ResultDTO
{
    public $notificationEmployeeByEmail = false;
    public $notificationClientByEmail = false;
    public $notificationClientBySms = [
        'isSent'  => false,
        'message' => '',
    ];


    public function toArray(){
       return get_object_vars($this);
    }
}
