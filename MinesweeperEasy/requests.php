<?php

session_start();
#if(!isset($_SESSION)){session_start();}

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

?>