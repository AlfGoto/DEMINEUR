<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketApp implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        // Logique à exécuter quand un client se connecte
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Logique à exécuter quand le serveur reçoit un message d'un client
    }

    public function onClose(ConnectionInterface $conn) {
        // Logique à exécuter quand un client se déconnecte
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Logique à exécuter en cas d'erreur
    }
}