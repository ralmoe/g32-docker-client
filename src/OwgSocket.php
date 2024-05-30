<?php

class OwgSocket
{
    private static $host = '3.120.177.98';
    private static $port = 4502;

    public function initSocket($serial, $pop, $token)
    {
        $requestHash = [
            'channel' => 'LISTEN_TO_GRILL',
            'data' => [
                'grillSerialNumber' => $serial,
                'pop' => $pop
            ],
            'token' => $token
        ];

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, self::$host, self::$port);
        socket_write($socket, json_encode($requestHash));

        return $socket;
    }
}