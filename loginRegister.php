<?php

#Connecting to the database
try{$db = new PDO('mysql:host=localhost;dbname=minesweeper;charset=utf8', 'root', 'root',
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);}
catch(Exception $e){
  die('erreur : '. $e->getMessage());
}

#check if a form was used
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  #REGISTER
  if(isset($_POST['registerPseudo'])){
    $registerPseudo = htmlspecialchars(strip_tags($_POST['registerPseudo']));
    $registerPassword = htmlspecialchars(strip_tags($_POST['registerPassword']));
    #check if there is not already this pseudo in the data base
    $request = "SELECT * FROM login WHERE pseudo = :pseudo";
    $sql = $db->prepare($request);
    $sql->bindParam(':pseudo', $registerPseudo, PDO::PARAM_STR);
    $sql->execute();
    if($sql->rowCount() > 0){
      echo ('le nom est déjà prit déso');
    }else{
      $request = 'INSERT INTO login(pseudo, password) VALUES(:pseudo, :password)';
      $sql = $db->prepare($request);
      $sql->execute([
        'pseudo'=>$registerPseudo,
        'password'=>$registerPassword
      ]);

      $zero = 0;
      $sqlc = "INSERT INTO stats (`pseudo`, `victories`, `bombsExploded`, `games`, `victoriesflagless` ,`victoriesaverages`) VALUES (:pseudo, 0, 0, 0, 0, 0)";
      $stmt = $db->prepare($sqlc);
      $stmt->bindParam(':pseudo', $registerPseudo, PDO::PARAM_STR);
      $stmt->execute();

      echo 'your account has been created';
      $_SESSION['isLogged'] = true;
      $_SESSION['user'] = $registerPseudo;
      echo "welcome $registerPseudo";
      if(isset($_POST['registerCookie'])){
        if(isset($_COOKIE['pseudo']) == false){
          setcookie('pseudo', $registerPseudo, time() + (365*24*60*60));
          echo 'Cookie set';
          Header('Location: '.$_SERVER['PHP_SELF']);
          header("Refresh:0");
        }
      }
    }

  }

  #LOGIN
  if(isset($_POST['loginPseudo'])){
    $loginPseudo = htmlspecialchars($_POST['loginPseudo']);
    $loginPassword = htmlspecialchars($_POST['loginPassword']);
    #check if there is not already this pseudo in the data base
    $request = "SELECT * FROM login WHERE pseudo = :pseudo";
    $sql = $db->prepare($request);
    $sql->bindParam(':pseudo', $loginPseudo, PDO::PARAM_STR);
    $sql->execute();
    if($sql->rowCount() > 0){
      $request = "SELECT password FROM login WHERE pseudo=:pseudo";
      $sql = $db->prepare($request);
      $sql->bindParam(":pseudo", $loginPseudo);
      $sql->execute();
      $result = $sql->fetch(PDO::FETCH_ASSOC);
      if($loginPassword == $result['password']){
        $_SESSION['isLogged'] = true;
        $_SESSION['user'] = $loginPseudo;
        echo "welcome $loginPseudo";
        if(isset($_POST['loginCookie'])){
          if(isset($_COOKIE['pseudo']) == false){
            setcookie('pseudo', $loginPseudo, time() + (365*24*60*60));
            echo 'Cookie set';
          }
        }
        echo "<script>document.addEventListener('DOMContentLoaded', () => {
          //Only show login and register Interface if not Logged
          let loginRegisterInterface = document.getElementById('loginRegisterInterface')
          let loggedInterface = document.getElementById('loggedInterface')
          loginRegisterInterface.classList.remove('visible')
          loginRegisterInterface.classList.add('hidden')
          loggedInterface.classList.remove('hidden')
          loggedInterface.classList.add('visible')
        })</script>";
        header("Refresh:0");
      }else{
        echo 'Wrong password';
      }
    }else{echo ('le compte n\'existe pas');}
  }



  //Unlogin
  if(isset($_POST['unlogButton'])){
    session_unset();
    setcookie('pseudo', 'pseudoDeleted', time() - (365*24*60*60));
    header("Refresh:0");
  }


};


?>