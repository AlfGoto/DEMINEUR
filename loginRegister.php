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
    if(strlen($registerPseudo)<5){
      echo 'sorry but this name has some HTML chars, change it pls';
      return;
    }
    $registerPassword = htmlspecialchars(strip_tags($_POST['registerPassword']));
    if(strlen($registerPassword)<5){
      echo 'sorry but this password has some HTML chars, change it pls';
      return;
    }
    #check if there is not already this pseudo in the data base
    $request = "SELECT * FROM login WHERE pseudo = :pseudo";
    $sql = $db->prepare($request);
    $sql->bindParam(':pseudo', $registerPseudo, PDO::PARAM_STR);
    $sql->execute();
    if($sql->rowCount() > 0){
      echo ('this name is already taken sorry');
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
          header('Location: ./index.php');
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
        header('Location: ./index.php');
      }else{
        echo 'Wrong password';
      }
    }else{echo ('le compte n\'existe pas');}
  }




};


?>




















<!DOCTYPE html>
<html>
<head>
        <link href="./style.css" rel="stylesheet">
        <script src='loginRegister.js'></script>

        <title>MineSweeper ULTIMATE</title>
        <link rel="icon" href="image\flagIcon.png" alt='icon of the website, its a redflag' />
        <h1 id='h1SEO'>Démineur Minesweeper</h1>
        <meta name="description" content="This is the ULTIMATE minesweeper game, lets see if you can be the best out of all players !">
        <meta name="author" content="AlfGoto">
        <meta name="title" content="Minesweeper">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="cache-control" content="no-cache, must-revalidate, max-age=0">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
    </head>

    <body class="bodyLogin">
        <h1 id='loginRegisterTitle'>This Website need you to be logged-in to work !</h1>

        
        <div id='loginRegisterInterface'>
          
        <nav id='navLoginRegister'>
        <div id='navLoginSelected'></div>
        <div id='loginClick'><p id='pLogin'>Login</p></div>
        <div id='registerClick'><p id='pRegister'>Register</p></div>
          
          
        </nav>
        <div id='registerDiv' class='loginAndRegisterDiv'>
            <h2 class='notSelectable' id='registerTitle'>Create your account</h2>
            <div id='registerContent'>
                <form id='registerForm' method='post'>
                    <div id='registerPseudoDiv'>
                        <label id='registerPseudoLabel'>Pseudo : </label>
                        <input id='registerPseudoInput' type='texte' name='registerPseudo' required minlength='5'
                            maxlength='15' class='textInput' pattern='^[^\s]+$'></input>
                    </div>
                    <div id='registerPasswordDiv'>
                        <label id='registerPasswordLabel'>Password : </label>
                        <input id='registerPasswordInput' type='password' name='registerPassword' required minlength='5'
                            maxlength='15' class='textInput' pattern='^[^\s]+$'></input>
                        <p class='notSelectable'>don't put your usual password, <br /> i'm still working on making this
                            site ultra
                            safe</p>
                    </div>
                    <div id='registerCookieDiv'>
                        <label id='registerCookieLabel'>Keep me sign in</label>
                        <input id='registerCookieInput' type='checkbox' name='registerCookie'></input>
                    </div>
                    <input type="submit" value="Register" class='submitButton'>
                </form>
            </div>
        </div>

        <div id='loginDiv' class='loginAndRegisterDiv'>
            <h2 class='notSelectable' id='loginTitle'>Login</h2>
            <div id='loginContent'>
                <form id='loginForm' method='post'>
                    <div id='loginPseudoDiv'>
                        <label id='loginPseudoLabel'>Pseudo : </label>
                        <input id='loginPseudoInput' type='texte' name='loginPseudo' required maxlength='15'
                            class='textInput'></input>
                    </div>
                    <div id='loginPasswordDiv'>
                        <label id='loginPasswordLabel'>Password : </label>
                        <input id='loginPasswordInput' type='password' name='loginPassword' required minlength='5'
                            maxlength='15' class='textInput'></input>
                    </div>
                    <div id='loginCookieDiv'>
                        <label id='loginCookieLabel'>Keep me sign in</label>
                        <input id='loginCookieInput' type='checkbox' name='loginCookie'></input>
                    </div>
                    <input type="submit" value="Login" class='submitButton'>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>