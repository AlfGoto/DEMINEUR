<?php

session_start();
#if(!isset($_SESSION)){session_start();}


include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);


#REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['idSquare'])){$currentId = $_POST['idSquare'];}



    //click bomb ?
    if ($_POST['request'] == 'click') {
        click($_SESSION['squares'][$_POST['idSquare']]);
    }

    //restart ?
    if ($_POST['request'] == 'restart'){
        exec('MinesweeperEasy\buildMinesweeper.php');
    }
}



function click($square){
    global $currentId, $neighbours, $db, $firstSquare;
    $isBomb = $square['isBomb'];
    if(isset($square['data'])){
        $data = $square['data'];
    }
    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        if($firstSquare == false){
            $sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
            $NBbomb = $db->prepare($sqlNBbomb);
            $NBbomb->execute([
               'pseudo'=>$_SESSION['user']
            ]);
        }
        return;
    } else {
        if ($data == 0) {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => false]);
            return;
        } else {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => false]);
            return;
        }
    }
}



?>