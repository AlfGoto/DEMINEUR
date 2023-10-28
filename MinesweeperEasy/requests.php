<?php

session_start();
#if(!isset($_SESSION)){session_start();}



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

    //games
    if ($_POST['request'] == 'games'){
        try{$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE,
            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
            $sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
            $NBgames = $db->prepare($sqlNBgames);
            $NBgames->execute([
                'pseudo'=>$_SESSION['user']
                ]);
            }
        catch(Exception $e){
            die('erreur : '. $e->getMessage());
        }
    }  
}



function click($square){
    global $currentId, $neighbours;
    $isBomb = $square['isBomb'];
    if(isset($square['data'])){
        $data = $square['data'];
    }
    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        return;
    } else {
        if ($data == 0) {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => 0]);
            return;
        } else {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => $data]);
            return;
        }
    }
}



?>