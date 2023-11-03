<?php

if(!isset($_SESSION)){session_start();}
include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

$_SESSION['flagused'] = false;

$sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
$NBgames = $db->prepare($sqlNBgames);
$NBgames->execute([
    'pseudo'=>$_SESSION['user']
]);