<?php

header("Cache-control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");









$width = 20;
$bombAmount = 70;
$squares = [];


#BUILD
$_SESSION['bombsArray'] = array_fill(0, $bombAmount, ['isBomb' => true]);
$_SESSION['validsArray'] = array_fill(0, $width*$width - $bombAmount, ['isBomb' => false]);
$_SESSION['gameArray'] = array_merge($_SESSION['bombsArray'], $_SESSION['validsArray']);
shuffle($_SESSION['gameArray']);









#REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'POST'){



    //click bomb ?
    if($_POST['request'] == 'click'){
        if($_SESSION['gameArray'][$_POST['idSquare']]['isBomb'] == true){
            echo json_encode(['isBomb' => true]);
        } else {
            echo json_encode(['isBomb' => false]);
        }
    }
}



    
