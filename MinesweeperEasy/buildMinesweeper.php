<?php



$width = 20;
$_SESSION['width'] = $width;
$bombAmount = 70;
$squares = [];
$total = 0;

#BUILD
function build(){
    global $width, $bombAmount, $squares;
    $_SESSION['bombsArray'] = array_fill(0, $bombAmount, ['isBomb' => true, 'checked' => false]);
    $_SESSION['validsArray'] = array_fill(0, $width*$width - $bombAmount, ['isBomb' => false, 'checked' => false]);
    $_SESSION['squares'] = array_merge($_SESSION['bombsArray'], $_SESSION['validsArray']);
    shuffle($_SESSION['squares']);


    //numbers on square
    for ($i = 0; $i < $width*$width; $i++) {
        $total = 0;
        $isLeftEdge = ($i % $width === 0);
        $isRightEdge = ($i % $width === $width -1);

        if ($_SESSION['squares'][$i]['isBomb'] == false) {
            if ($i > 0 && !$isLeftEdge && $_SESSION['squares'][$i -1]['isBomb']) $total++;
            if ($i > 19 && !$isRightEdge && $_SESSION['squares'][$i +1 -$width]['isBomb']) $total++;
            if ($i > 20 && $_SESSION['squares'][$i - $width]['isBomb']) $total++;
            if ($i > 21 && !$isLeftEdge && $_SESSION['squares'][$i  -1 -$width]['isBomb']) $total++;
            if ($i < 398 && !$isRightEdge && $_SESSION['squares'][$i  +1]['isBomb']) $total++;
            if ($i < 380 && !$isLeftEdge && $_SESSION['squares'][$i  -1 +$width]['isBomb']) $total++;
            if ($i < 378 && !$isRightEdge && $_SESSION['squares'][$i  +1 +$width]['isBomb']) $total++;
            if ($i < 379 && $_SESSION['squares'][$i  +$width]['isBomb']) $total++;
            if ($i === 398 && $_SESSION['squares'][$i  +1]['isBomb']) $total++;
            if ($i === 379 && $_SESSION['squares'][$i  +20]['isBomb']) $total++;
            if ($i === 378 && $_SESSION['squares'][$i  +21]['isBomb']) $total++;
            if ($i === 21 && $_SESSION['squares'][$i  -21]['isBomb']) $total++;
            if ($i === 20 && $_SESSION['squares'][$i  -20]['isBomb']) $total++;
            $_SESSION['squares'][$i]['data'] = $total;
        }
    }
}
build();






?>