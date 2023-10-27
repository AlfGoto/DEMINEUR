<?php



$width = 20;
$bombAmount = 70;
$squares = [];


#BUILD
$_SESSION['bombsArray'] = array_fill(0, $bombAmount, ['isBomb' => true]);
$_SESSION['validsArray'] = array_fill(0, $width*$width - $bombAmount, ['isBomb' => false]);
$_SESSION['gameArray'] = array_merge($_SESSION['bombsArray'], $_SESSION['validsArray']);
shuffle($_SESSION['gameArray']);





?>