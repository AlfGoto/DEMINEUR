<?php


if(isset($_POST['mute'])){
        setcookie('mute', $_POST['mute'], time() + (365*24*60*60));
}