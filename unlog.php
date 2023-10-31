<?php



//Unlogin
  if(isset($_POST['unlogButton'])){
    session_unset();
    setcookie('pseudo', 'pseudoDeleted', time() - (365*24*60*60));
    header('Location: ./loginRegister.php');
  }