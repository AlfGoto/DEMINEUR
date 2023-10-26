<?php
session_start();

include 'GlobalsVars.php';

if(isset($_POST['flagused'])){
    $_SESSION['flagused'] = $_POST['flagused'];
}

try{$db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE,
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
}
catch(Exception $e){
    die('erreur : '. $e->getMessage());
}




//if victory
if(isset($_POST['victory'])){
    if($_POST['victory'] == true){
        $sqlQuery = 'INSERT INTO times (id, pseudo, time) VALUES (NULL, :pseudo, :time)';
        $insertTimes = $db->prepare($sqlQuery);
        $insertTimes->execute([
            'pseudo'=>$_SESSION['user'],
            'time'=>$_POST['elapsedTime'],
        ]);
        $sqlNBvictory = 'UPDATE stats SET victories = victories + 1 WHERE pseudo = :pseudo';
        $NBvictory = $db->prepare($sqlNBvictory);
        $NBvictory->execute([
            'pseudo'=>$_SESSION['user']
        ]);
        if($_SESSION['flagused'] == false){
            $sqlNBflagless = 'UPDATE stats SET victoriesflagless = victoriesflagless + 1 WHERE pseudo = :pseudo';
                $NBflagless = $db->prepare($sqlNBflagless);
                $NBflagless->execute([
                    'pseudo'=>$_SESSION['user']
                ]);
        }else{
            $_SESSION['flagused'] == false;
        } 
    }
    
        unset($_POST['victory']);
}

    

//if bomb
if(isset($_POST['bomb'])){
    if($_POST['bomb'] == true){
        $sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
        $NBbomb = $db->prepare($sqlNBbomb);
        $NBbomb->execute([
            'pseudo'=>$_SESSION['user']
        ]);
        $victory = false;
        };
    unset($_POST['bomb']);
}

//if games
if(isset($_POST['game'])){
    $sqlNBgames = 'UPDATE stats SET games = games + 1 WHERE pseudo = :pseudo';
        $NBgames = $db->prepare($sqlNBgames);
        $NBgames->execute([
            'pseudo'=>$_SESSION['user']
        ]);
    }


    
    
    
    
    
    



?>