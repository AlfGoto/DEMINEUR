<?php

session_start();
#if(!isset($_SESSION)){session_start();}





function click($square){
    global $currentId, $neighbours, $db;

    if($_SESSION['firstSquare'] == true){
        $_SESSION['firstSquare'] = false;
        $_SESSION['timerStart'] = round(microtime(true) * 1000);
    }

    $isBomb = $square['isBomb'];

    if(isset($square['data'])){
        $data = $square['data'];
    }



    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        if($_SESSION['firstSquare'] == false)include('./statsBombExploded.php');
        return;
    } else {
        


        if ($data == 0) {
            if($_SESSION['squares'][$currentId]['checked'] == true){
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => false,'return'=> true]);
                return;
            }
            $_SESSION['squares'][$currentId]['checked'] = true;
            $_SESSION['squareLeft']--;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => true]);
                include('./winTimer.php');
                include('./statsWin.php');
            }else {
                echo json_encode(['isBomb' => false, 'data' => 0, 'victory' => false]);
            }
            return;
        } else {



            if($_SESSION['squares'][$currentId]['checked'] == true){
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => false,'return'=> true]);
                return;
            }
            $_SESSION['squares'][$currentId]['checked'] = true;
            $_SESSION['squareLeft']--;
            if ($_SESSION['squareLeft']  == 0){
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => true]);
                include('./winTimer.php');
                include('./statsWin.php');
            }else{
                echo json_encode(['isBomb' => false, 'data' => $data, 'victory' => false]);
            }
            return;
        }
        if($_SESSION['firstSquare'] == true){
            $_SESSION['firstSquare'] = false;
        }
    }
}

$currentId = $_POST['idSquare'];
click($_SESSION['squares'][$_POST['idSquare']]);

?>