<?php

if(!isset($_SESSION)){session_start();}

if ($_POST['request'] == 'allBomb') {
    allBomb();
}

function allBomb(){
    $bombsPosition = [];
    for($i = 0; $i < $_SESSION['width'] * $_SESSION['width']; $i++){
        if($_SESSION['squares'][$i]['isBomb']){
            array_push($bombsPosition, $i);
        }
    }
    echo json_encode($bombsPosition);
}