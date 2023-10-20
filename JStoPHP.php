<?php
session_start();




$elapsedTime = 5;

//To not have any duplicate (it doesnt work too well)
if($elapsedTime != $_POST ['elapsedTime']){
    $elapsedTime = $_POST['elapsedTime'];

    if(isset($_POST['victory'])){
        $victory = $_POST['victory'];
    }
    if(isset($_POST['bomb'])){
        $bomb = $_POST['bomb'];
    }
    if(isset($_POST['flagless'])){
        $flagless = $_POST['flagless'];
    }

    try{$db = new PDO('mysql:host=localhost;dbname=minesweeper;charset=utf8',
        'root',
        'root',
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    }
    catch(Exception $e){
        die('erreur : '. $e->getMessage());
    }


    //if victory
    if($victory == true){
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
        if($flagless == true){
            $sqlNBflagless = 'UPDATE stats SET victoriesflagless = victoriesflagless + 1 WHERE pseudo = :pseudo';
            $NBflagless = $db->prepare($sqlNBflagless);
            $NBflagless->execute([
                'pseudo'=>$_SESSION['user']
            ]);
        }

        $flagless = false;
        $victory = false;
        };

    //if bomb
    if($bomb == true){
        $sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
        $NBbomb = $db->prepare($sqlNBbomb);
        $NBbomb->execute([
            'pseudo'=>$_SESSION['user']
        ]);
        $sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
        $NBgames = $db->prepare($sqlNBgames);
        $NBgames->execute([
            'pseudo'=>$_SESSION['user']
        ]);
        $victory = false;
        };
    }
    
    
    
    



?>