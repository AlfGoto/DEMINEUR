<?php
namespace MyApp;



use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketApp implements MessageComponentInterface {
    private $clients = [];

    public function onOpen(ConnectionInterface $conn) {
        // Générer un identifiant unique pour la connexion
        $connId = uniqid();

        //StartingBuild
        $cookieArray = $conn->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
            echo $SESSID . "\n";

            build($SESSID);
        

        
        // Stocker la connexion dans le tableau clients avec l'identifiant unique comme clé
        $this->clients[$connId] = $conn;
    }




    //MESSAGE
    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg, true);

        //if BUILD
        if($msg['request'] == 'build'){
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
            echo $SESSID . "\n";

            build($SESSID);
            return;
        }

        //if CLICK
        if($msg['request'] == 'click'){
            global $squares;
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
            echo $SESSID . "\n";
            $id = $msg['id'];
            print_r($squares[$SESSID][$id]);
            


            if($squares[$SESSID][$id]['isBomb'] == true){
                $from->send("C'est une BOMBE");
                return;
            }
            if($squares[$SESSID][$id]["data"] == 0){
                $from->send("square " . $id . " data 0");
                return;
            }
            if($squares[$SESSID][$id]["data"] > 0){
                $data = $squares[$SESSID][$id]["data"];
                $from->send("square " . $id . "| data :" . $data);
                return;
            }
        } 
        
    }

    



    public function onClose(ConnectionInterface $conn) {
        $cookieArray = $conn->httpRequest->getHeader('Cookie');
        $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
        unset($squares[$SESSID]);
        // Trouver et supprimer la connexion du tableau clients lorsqu'elle se déconnecte
        foreach ($this->clients as $connId => $client) {
            if ($conn === $client) {
                unset($this->clients[$connId]);
                break;
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Logique à exécuter en cas d'erreur
    }
}






function build($SESSID){

    $width = 20;
    global $squares;
    $squares[$SESSID] = [];
    $total = 0;
    $bombAmount = 70;


    $squares['bombsArray'] = array_fill(0, $bombAmount, ['isBomb' => true, 'checked' => false]);
    $squares['validsArray'] = array_fill(0, $width*$width - $bombAmount, ['isBomb' => false, 'checked' => false]);
    $squares[$SESSID] = array_merge($squares['bombsArray'], $squares['validsArray']);
    shuffle($squares[$SESSID]);

    //numbers on square
    for ($i = 0; $i < $width*$width; $i++) {
        $total = 0;
        $isLeftEdge = ($i % $width === 0);
        $isRightEdge = ($i % $width === $width -1);

        if ($squares[$SESSID][$i]['isBomb'] == false) {
            if ($i > 0 && !$isLeftEdge && $squares[$SESSID][$i -1]['isBomb']) $total++;
            if ($i > 19 && !$isRightEdge && $squares[$SESSID][$i +1 -$width]['isBomb']) $total++;
            if ($i > 20 && $squares[$SESSID][$i - $width]['isBomb']) $total++;
            if ($i > 21 && !$isLeftEdge && $squares[$SESSID][$i  -1 -$width]['isBomb']) $total++;
            if ($i < 398 && !$isRightEdge && $squares[$SESSID][$i  +1]['isBomb']) $total++;
            if ($i < 380 && !$isLeftEdge && $squares[$SESSID][$i  -1 +$width]['isBomb']) $total++;
            if ($i < 378 && !$isRightEdge && $squares[$SESSID][$i  +1 +$width]['isBomb']) $total++;
            if ($i < 379 && $squares[$SESSID][$i  +$width]['isBomb']) $total++;
            if ($i === 398 && $squares[$SESSID][$i  +1]['isBomb']) $total++;
            if ($i === 379 && $squares[$SESSID][$i  +20]['isBomb']) $total++;
            if ($i === 378 && $squares[$SESSID][$i  +21]['isBomb']) $total++;
            if ($i === 21 && $squares[$SESSID][$i  -21]['isBomb']) $total++;
            if ($i === 20 && $squares[$SESSID][$i  -20]['isBomb']) $total++;
            $squares[$SESSID][$i]['data'] = $total;
        }
    }


    print_r($squares[$SESSID]);



    /*
    $squares[$SESSID]
    
    
    
    
    ;
    $_SESSION['firstSquare'] = true;
    $_SESSION['bombAmount'] = $bombAmount;
    $_SESSION['squareLeft'] = $width*$width - $bombAmount;
    $_SESSION['flagused'] = false;
    $_SESSION['firstSquare'] = true;
    $_SESSION['bombsArray'] = [];
    $_SESSION['validsArray'] = [];
    $_SESSION['squares'] = [];

    */
    
}