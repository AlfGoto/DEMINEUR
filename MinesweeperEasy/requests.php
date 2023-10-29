<?php

session_start();
#if(!isset($_SESSION)){session_start();}





function click($square){
    global $currentId, $neighbours, $db;

    if($_SESSION['firstSquare'] == true){
        $_SESSION['firstSquare'] = false;
        $_SESSION['timerStart'] = round(microtime(true) * 1000);
    }

    if($_SESSION['squares'][$currentId]['checked'] == true){
        echo json_encode(['return'=> true]);
        return;}

    $isBomb = $square['isBomb'];

    if(isset($square['data'])){
        $data = $square['data'];
    }

    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        if($_SESSION['firstSquare'] == false)include('./statsBombExploded.php');
        return;
    } else {
        $_SESSION['squareLeft']--;
        if ($data == 0) {
            $_SESSION['squares'][$currentId]['checked'] = true;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => true]);
                include('./winTimer.php');
                include('./statsWin.php');
            }else {
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => false]);
            }
            return;
        } else {
            $_SESSION['squares'][$currentId]['checked'] = true;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => true]);
                include('./winTimer.php');
                include('./statsWin.php');
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