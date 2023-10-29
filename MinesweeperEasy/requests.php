<?php

session_start();
#if(!isset($_SESSION)){session_start();}





function click($square){
    global $currentId, $neighbours, $db, $firstSquare;
    if($_SESSION['squares'][$currentId]['checked'] == true){
        echo json_encode(['return'=> true]);
        return;}
    $isBomb = $square['isBomb'];
    if(isset($square['data'])){
        $data = $square['data'];
    }
    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        if($firstSquare == false){
            include('../GlobalsVars.php');
            $db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
            $sqlNBbomb = 'UPDATE stats SET bombsExploded = bombsExploded + 1 WHERE pseudo = :pseudo';
            $NBbomb = $db->prepare($sqlNBbomb);
            $NBbomb->execute([
               'pseudo'=>$_SESSION['user']
            ]);
        }
        return;
    } else {
        $_SESSION['squareLeft']--;
        if ($data == 0) {
            $_SESSION['squares'][$currentId]['checked'] = true;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => true]);
            }else {
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => false]);
            }
            return;
        } else {
            $_SESSION['squares'][$currentId]['checked'] = true;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => true]);
            }else{
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => false]);
            }
            return;
        }
    }
}
$currentId = $_POST['idSquare'];
click($_SESSION['squares'][$_POST['idSquare']]);

?>