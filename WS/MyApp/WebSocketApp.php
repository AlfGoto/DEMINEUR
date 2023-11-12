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

            build($SESSID);
            return;
        }

        //if CLICK
        if($msg['request'] == 'click'){
            global $squares, $squaresLeft;
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
            $id = $msg['id'];
            if($squares[$SESSID][$id]['checked'] == true){return;}
            $squares[$SESSID][$id]['checked'] = true;
            $squaresLeft[$SESSID]--;

            


            if($squares[$SESSID][$id]['isBomb'] == true){
                $info = array(
                    'request' => 'isBomb',
                    'id' => $id
                );
                $clickResponse = json_encode($info);
                $from->send($clickResponse);
                return;
            }
            if($squares[$SESSID][$id]["data"] == 0){
                if($squaresLeft[$SESSID] == 0){
                    global $timer;
                    $timer[$SESSID]['finish'] = round(microtime(true) * 1000) - $timer[$SESSID]['start'];
                    $hashedTime = password_hash($timer[$SESSID]['finish'], PASSWORD_DEFAULT);
                    $info = array(
                        'request' => 'data0',
                        'id' => $id,
                        'victory'=> true,
                        'time' => $timer[$SESSID]['finish'],
                        'hashed' => $hashedTime
                    );
                    $clickResponse = json_encode($info);
                    $from->send($clickResponse);
                    return;
                }
                $info = array(
                    'request' => 'data0',
                    'id' => $id
                );
                $clickResponse = json_encode($info);
                $from->send($clickResponse);
                return;
            }
            if($squares[$SESSID][$id]["data"] > 0){
                global $timer, $squares, $firstSquare;
                if($firstSquare[$SESSID] == true){
                    $firstSquare[$SESSID] = false;
                    $timer[$SESSID]['start'] = round(microtime(true) * 1000);
                }
                $data = $squares[$SESSID][$id]["data"];
                if($squaresLeft[$SESSID] == 0){
                    global $timer;
                    $timer[$SESSID]['finish'] = round(microtime(true) * 1000) - $timer[$SESSID]['start'];
                    $hashedTime = password_hash($timer[$SESSID]['finish'], PASSWORD_DEFAULT);
                    $info = array(
                        'request' => 'data0',
                        'id' => $id,
                        'data' => $data,
                        'victory'=> true,
                        'time' => $timer[$SESSID]['finish'],
                        'hashed' => $hashedTime
                    );
                    $clickResponse = json_encode($info);
                    $from->send($clickResponse);
                    return;
                }
                $info = array(
                    'request' => 'isData',
                    'id' => $id,
                    'data' => $data
                );
                $clickResponse = json_encode($info);
                $from->send($clickResponse);
                return;
            }
        } 

        //if ALLBOMB
        if($msg['request'] == 'allBomb'){
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);

            allBomb($SESSID, $from);
        }
        
    }

    



    public function onClose(ConnectionInterface $conn) {
        global $squares, $timer, $squaresLeft;
        $cookieArray = $conn->httpRequest->getHeader('Cookie');
        $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
        unset($squares[$SESSID]);
        unset($timer[$SESSID]);
        unset($squaresLeft[$SESSID]);
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

    global $width, $squares, $squaresLeft, $firstSquare;
    $width = 20;
    $squares[$SESSID] = [];
    $bombAmount = 70;
    $squares['bombsArray'] = [];
    $squares['validsArray'] = [];
    $firstSquare[$SESSID] = true;
    $squaresLeft[$SESSID] = $width*$width - $bombAmount;


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
}

function allBomb($SESSID, $from){
    global $width, $squares;
    if(isset($bombsPosition[$SESSID])){unset($bombsPosition[$SESSID]);}
    $bombsPosition = [];
    $bombsPosition[$SESSID] = [];
    for($i = 0; $i < $width * $width; $i++){
        if($squares[$SESSID][$i]['isBomb'] == true){
            array_push($bombsPosition[$SESSID], $i);
        }
    }
    $info = array(
        'request' => 'allBombArray',
        'array' => $bombsPosition[$SESSID]
    );
    $clickResponse = json_encode($info);
    $from->send($clickResponse);
    return;
}