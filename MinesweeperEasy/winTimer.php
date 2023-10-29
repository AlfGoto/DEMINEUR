<?php

if(!isset($_SESSION)){session_start();}
include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

$elapsedTime = round(microtime(true) * 1000) - $_SESSION['timerStart'];

$sqlQuery = 'INSERT INTO times (id, pseudo, time) VALUES (NULL, :pseudo, :time)';
    $insertTimes = $db->prepare($sqlQuery);
    $insertTimes->execute([
        'pseudo'=>$_SESSION['user'],
        'time'=>$elapsedTime,
    ]);