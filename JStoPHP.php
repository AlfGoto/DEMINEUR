<?php
session_start();

$victory = $_POST['victory'];

$elapsedTime = 5;

if($elapsedTime != $_POST ['elapsedTime']){
    $elapsedTime = $_POST['elapsedTime'];
    
    echo "Data received successfully! ";
    echo $elapsedTime;
    echo ' ';
    echo $_SESSION['user'];

    try{$db = new PDO('mysql:host=localhost;dbname=minesweeper;charset=utf8',
        'root',
        'root',
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    }
    catch(Exception $e){
        die('erreur : '. $e->getMessage());
    }

    $sqlQuery = 'INSERT INTO times (id, pseudo, time) VALUES (NULL, :pseudo, :time)';
    $insertTimes = $db->prepare($sqlQuery);
    $insertTimes->execute([
        'pseudo'=>$_SESSION['user'],
        'time'=>$elapsedTime
    ]);
    $sqlNBvictory = 'UPDATE stats SET victories = victories + 1 WHERE pseudo = :pseudo';
    $NBvictory = $db->prepare($sqlNBvictory);
    $NBvictory->execute([
        'pseudo'=>$_SESSION['user']
    ]);
    $sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
    $NBgames = $db->prepare($sqlNBgames);
    $NBgames->execute([
        'pseudo'=>$_SESSION['user']
    ]);
    $victory = false;
}


?>