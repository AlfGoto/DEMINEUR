<?php

if(!isset($_SESSION)){session_start();}
include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

$sqlNBvictory = 'UPDATE stats SET victories = victories + 1 WHERE pseudo = :pseudo';
    $NBvictory = $db->prepare($sqlNBvictory);
    $NBvictory->execute([
        'pseudo'=>$_SESSION['user']
    ]);