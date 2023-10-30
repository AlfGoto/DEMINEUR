<?php

if(!isset($_SESSION)){session_start();}
include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);


$sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
        $NBbomb = $db->prepare($sqlNBbomb);
        $NBbomb->execute([
            'pseudo'=>$_SESSION['user']
        ]);
        

