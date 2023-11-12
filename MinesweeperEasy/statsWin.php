<?php

if(!isset($_SESSION)){session_start();}
include('../GlobalsVars.php');
$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

if(password_verify($_POST['time'], $_POST['hashed'])){
    $sqlNBvictory = 'UPDATE stats SET victories = victories + 1 WHERE pseudo = :pseudo';
    $NBvictory = $db->prepare($sqlNBvictory);
    $NBvictory->execute([
        'pseudo'=>$_SESSION['user']
    ]);

    if ($_SESSION['flagused']){}else{
         $sqlNBflagless = 'UPDATE stats SET victoriesflagless = victoriesflagless + 1 WHERE pseudo = :pseudo';
                    $NBflagless = $db->prepare($sqlNBflagless);
                    $NBflagless->execute([
                        'pseudo'=>$_SESSION['user']
                    ]);
     }

    $elapsedTime = $_POST['time'];

    $sqlQuery = 'INSERT INTO times (id, pseudo, time) VALUES (NULL, :pseudo, :time)';
        $insertTimes = $db->prepare($sqlQuery);
        $insertTimes->execute([
            'pseudo'=>$_SESSION['user'],
            'time'=>$elapsedTime,
        ]);
}else{
    return;
}

