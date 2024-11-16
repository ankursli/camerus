<?php


namespace App\Library\Services\Contracts;


interface SendRentServiceInterface
{
    public function sendRequest($xml);
}