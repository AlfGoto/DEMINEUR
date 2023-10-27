<?php

session_start();
#if(!isset($_SESSION)){session_start();}



#REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['idSquare'])){$currentId = $_POST['idSquare'];}



    //click bomb ?
    if ($_POST['request'] == 'click') {
        click($_SESSION['squares'][$_POST['idSquare']]);
    }
}



function click($square){
    global $currentId, $neighbours;
    $isBomb = $square['isBomb'];
    if(isset($square['data'])){
        $data = $square['data'];
    }
    if ($isBomb) {
        echo json_encode(['isBomb' => true]);
        return;
    } else {
        if ($data == 0) {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => 0]);
            return;
        } else {
            $_SESSION['squares'][$currentId]['checked'] = true;
            echo json_encode(['isBomb' => false, 'data' => $data]);
            return;
        }
    }
}



?>