<?php
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\WebSocketApp;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketApp()
        )
    ),
    8080 // Port sur lequel votre serveur WebSocket écoutera
);

$server->run();