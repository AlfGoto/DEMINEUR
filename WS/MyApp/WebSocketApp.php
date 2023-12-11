<?php
namespace MyApp;



use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;


include('../GlobalsVars.php');

class WebSocketApp implements MessageComponentInterface
{
    private $clients = [];


    public function onOpen(ConnectionInterface $conn)
    {
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
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg, true);


        //if BONJOUR
        if ($msg['request'] == 'bonjour') {
            global $pseudo;
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);

            $pseudo[$SESSID] = $msg['pseudo'];
            print('bonjour ' . $pseudo[$SESSID] . " | ");
            return;
        }

        //if BUILD
        if ($msg['request'] == 'build') {
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);

            build($SESSID);
            return;
        }

        //if FLAGUSED
        if ($msg['request'] == 'flagused') {
            global $flagused;
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);

            $flagused[$SESSID] = true;
            return;
        }











        //if CLICK
        if ($msg['request'] == 'click') {
            global $squares, $squaresLeft, $firstSquare, $secondSquare;
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
            $id = $msg['id'];
            if ($squares[$SESSID][$id]['checked'] == true) {
                return;
            }
            $squares[$SESSID][$id]['checked'] = true;
            $squaresLeft[$SESSID]--;

            if ($squares[$SESSID][$id]['isBomb'] == true) {
                $info = array(
                    'request' => 'isBomb',
                    'id' => $id
                );
                if ($firstSquare[$SESSID] == true || $secondSquare[$SESSID] == true) {
                } else {
                    bombstats($SESSID);
                }
                $clickResponse = json_encode($info);
                $from->send($clickResponse);
                return;
            }
            if ($squares[$SESSID][$id]["data"] == 0) {
                if ($squaresLeft[$SESSID] == 0) {
                    global $timer;
                    $timer[$SESSID]['finish'] = round(microtime(true) * 1000) - $timer[$SESSID]['start'];
                    $info = array(
                        'request' => 'data0',
                        'id' => $id,
                        'victory' => true,
                    );
                    $clickResponse = json_encode($info);
                    $from->send($clickResponse);
                    winstats($SESSID, $timer[$SESSID]['finish']);
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
            if ($squares[$SESSID][$id]["data"] > 0) {
                global $timer, $squares, $firstSquare, $secondSquare;
                if ($secondSquare[$SESSID] == true) {
                    $secondSquare[$SESSID] = false;
                    gamesstats($SESSID);
                }
                if ($firstSquare[$SESSID] == true) {
                    $firstSquare[$SESSID] = false;
                    $secondSquare[$SESSID] = true;
                    $timer[$SESSID]['start'] = round(microtime(true) * 1000);
                }
                $data = $squares[$SESSID][$id]["data"];
                if ($squaresLeft[$SESSID] == 0) {
                    global $timer;
                    $timer[$SESSID]['finish'] = round(microtime(true) * 1000) - $timer[$SESSID]['start'];
                    $info = array(
                        'request' => 'data0',
                        'id' => $id,
                        'data' => $data,
                        'victory' => true,
                    );
                    $clickResponse = json_encode($info);
                    $from->send($clickResponse);
                    winstats($SESSID, $timer[$SESSID]['finish']);
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
        if ($msg['request'] == 'allBomb') {
            $cookieArray = $from->httpRequest->getHeader('Cookie');
            $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);

            allBomb($SESSID, $from);
        }

    }





    public function onClose(ConnectionInterface $conn)
    {
        global $squares, $timer, $squaresLeft, $pseudo, $flagused, $firstSquare, $secondSquare, $bombsArray, $validsArray;
        $cookieArray = $conn->httpRequest->getHeader('Cookie');
        $SESSID = str_replace('PHPSESSID=', "", $cookieArray[0]);
        unset($squares[$SESSID]);
        unset($timer[$SESSID]);
        unset($squaresLeft[$SESSID]);
        unset($pseudo[$SESSID]);
        unset($flagused[$SESSID]);
        unset($firstSquare[$SESSID]);
        unset($secondSquare[$SESSID]);
        unset($bombsArray[$SESSID]);
        unset($validsArray[$SESSID]);
        // Trouver et supprimer la connexion du tableau clients lorsqu'elle se déconnecte
        foreach ($this->clients as $connId => $client) {
            if ($conn === $client) {
                unset($this->clients[$connId]);
                break;
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}




function build($SESSID)
{

    global $width, $squares, $squaresLeft, $firstSquare, $flagused, $secondSquare, $pseudo;

    if (isset($pseudo[$SESSID])) {
        print("\n BUILD Function " . $pseudo[$SESSID] . " | ");
    } else {
        print("\n New player has joined | ");
    }


    $width = 20;
    $squares[$SESSID] = [];
    $flagused[$SESSID] = false;
    $bombAmount = 70;
    $bombsArray[$SESSID] = [];
    $validsArray[$SESSID] = [];
    $firstSquare[$SESSID] = true;
    $secondSquare[$SESSID] = false;
    $squaresLeft[$SESSID] = $width * $width - $bombAmount;


    $bombsArray[$SESSID] = array_fill(0, $bombAmount, ['isBomb' => true, 'checked' => false]);
    $validsArray[$SESSID] = array_fill(0, $width * $width - $bombAmount, ['isBomb' => false, 'checked' => false]);
    $squares[$SESSID] = array_merge($bombsArray[$SESSID], $validsArray[$SESSID]);
    shuffle($squares[$SESSID]);

    //numbers on square
    for ($i = 0; $i < $width * $width; $i++) {
        $total = 0;
        $isLeftEdge = ($i % $width === 0);
        $isRightEdge = ($i % $width === $width - 1);

        if ($squares[$SESSID][$i]['isBomb'] == false) {
            if ($i > 0 && !$isLeftEdge && $squares[$SESSID][$i - 1]['isBomb'])
                $total++;
            if ($i > 19 && !$isRightEdge && $squares[$SESSID][$i + 1 - $width]['isBomb'])
                $total++;
            if ($i > 20 && $squares[$SESSID][$i - $width]['isBomb'])
                $total++;
            if ($i > 21 && !$isLeftEdge && $squares[$SESSID][$i - 1 - $width]['isBomb'])
                $total++;
            if ($i < 398 && !$isRightEdge && $squares[$SESSID][$i + 1]['isBomb'])
                $total++;
            if ($i < 380 && !$isLeftEdge && $squares[$SESSID][$i - 1 + $width]['isBomb'])
                $total++;
            if ($i < 378 && !$isRightEdge && $squares[$SESSID][$i + 1 + $width]['isBomb'])
                $total++;
            if ($i < 379 && $squares[$SESSID][$i + $width]['isBomb'])
                $total++;
            if ($i === 398 && $squares[$SESSID][$i + 1]['isBomb'])
                $total++;
            if ($i === 379 && $squares[$SESSID][$i + 20]['isBomb'])
                $total++;
            if ($i === 378 && $squares[$SESSID][$i + 21]['isBomb'])
                $total++;
            if ($i === 21 && $squares[$SESSID][$i - 21]['isBomb'])
                $total++;
            if ($i === 20 && $squares[$SESSID][$i - 20]['isBomb'])
                $total++;
            $squares[$SESSID][$i]['data'] = $total;
        }
    }
}

function allBomb($SESSID, $from)
{
    global $width, $squares;
    if (isset($bombsPosition[$SESSID])) {
        unset($bombsPosition[$SESSID]);
    }
    $bombsPosition = [];
    $bombsPosition[$SESSID] = [];
    for ($i = 0; $i < $width * $width; $i++) {
        if ($squares[$SESSID][$i]['isBomb'] == true) {
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

function winstats($SESSID, $elapsedTime)
{
    global $pseudo, $flagused;

    print("WINSTATS " . $pseudo[$SESSID] . " | ");

    //include('../GlobalsVars.php');
    $db = new PDO("mysql:host=localhost;dbname=" . $GLOBALS['DBNAME'], $GLOBALS['DBPSEUDO'], $GLOBALS['DBCODE'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $sqlNBvictory = 'UPDATE stats SET victories = victories + 1 WHERE pseudo = :pseudo';
    $NBvictory = $db->prepare($sqlNBvictory);
    $NBvictory->execute([
        'pseudo' => $pseudo[$SESSID]
    ]);

    if ($flagused[$SESSID]) {
    } else {
        $sqlNBflagless = 'UPDATE stats SET victoriesflagless = victoriesflagless + 1 WHERE pseudo = :pseudo';
        $NBflagless = $db->prepare($sqlNBflagless);
        $NBflagless->execute([
            'pseudo' => $pseudo[$SESSID]
        ]);
    }

    print_r($elapsedTime);

    $sqlQuery = 'INSERT INTO times (id, pseudo, time) VALUES (NULL, :pseudo, :time)';
    $insertTimes = $db->prepare($sqlQuery);
    $insertTimes->execute([
        'pseudo' => $pseudo[$SESSID],
        'time' => $elapsedTime,
    ]);
    $db->connection = null;
}

function bombstats($SESSID)
{
    global $pseudo;

    print('BOMBSTATS ' . $pseudo[$SESSID] . ' | ');

    //include('../GlobalsVars.php');
    $db = new PDO("mysql:host=localhost;dbname=" . $GLOBALS['DBNAME'], $GLOBALS['DBPSEUDO'], $GLOBALS['DBCODE'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


    $sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
    $NBbomb = $db->prepare($sqlNBbomb);
    $NBbomb->execute([
        'pseudo' => $pseudo[$SESSID]
    ]);
    $db->connection = null;

}

function gamesstats($SESSID)
{
    global $pseudo;

    print('GAMESSTATS ' . $pseudo[$SESSID] . ' | ');

    //include('../GlobalsVars.php');
    $db = new PDO("mysql:host=localhost;dbname=" . $GLOBALS['DBNAME'], $GLOBALS['DBPSEUDO'], $GLOBALS['DBCODE'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
    $NBgames = $db->prepare($sqlNBgames);
    $NBgames->execute([
        'pseudo' => $pseudo[$SESSID]
    ]);
    $db->connection = null;
}
