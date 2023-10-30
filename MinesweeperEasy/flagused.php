<?php

if(!isset($_SESSION)){session_start();}

$_SESSION['flagused'] = true;

session_write_close();