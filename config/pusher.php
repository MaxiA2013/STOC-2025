<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pusher\Pusher as PusherClient;

class ConexionPusher
{
    private $app_id;
    private $app_key;
    private $app_secret;
    private $app_cluster;

    public function __construct()
    {
        $this->app_id = '2196877';
        $this->app_key = '30891e0e5a67798ca12f';
        $this->app_secret = '805a1ce44fc2c17419ef';
        $this->app_cluster = 'sa1';
    }

    public function obtenerCliente(): PusherClient
    {
        return new PusherClient(
            $this->app_key,
            $this->app_secret,
            $this->app_id,
            [
                'cluster' => $this->app_cluster,
                'useTLS' => true
            ]
        );
    }
}