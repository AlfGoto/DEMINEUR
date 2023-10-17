<?php
session_start();

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
}


?>