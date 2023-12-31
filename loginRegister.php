<?php

header("Cache-control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_start(); 

include 'GlobalsVars.php';



#Detect mobile device and redirect them
function isMobileDevice() {
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $mobileKeywords = array('Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry', 'BB10', 'Symbian', 'Opera Mini', 'Mobile', 'Mobile Safari', 'Tablet', 'IEMobile');

  foreach ($mobileKeywords as $keyword) {
      if (stripos($userAgent, $keyword) !== false) {
          return true;
      }
  }
  return false;
}

if (isMobileDevice()) {
  header('Location: ./welcomeDearMobileUser.html');
} 




#Connecting to the database
try{$db = new PDO("mysql:host=localhost;dbname=" .$GLOBALS['DBNAME'], $GLOBALS['DBPSEUDO'], $GLOBALS['DBCODE'],
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
    $hashedRegisterPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
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
        'password'=>$hashedRegisterPassword
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
      header('Location: ./index.php');
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
      if(password_verify($loginPassword, $result['password'])){
        $_SESSION['isLogged'] = true;
        $_SESSION['user'] = $loginPseudo;
        echo "welcome $loginPseudo";
        if(isset($_POST['loginCookie'])){
          if(isset($_COOKIE['pseudo']) == false){
            setcookie('pseudo', $loginPseudo, time() + (365*24*60*60));
            echo 'Cookie set';
          }
        }
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
        <script src='loginRegister.js'></script>
        <link href="./style.css" rel="stylesheet"></link>

        <div>
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
        </div>
        
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
            <div id='registerContent'>
                <form id='registerForm' method='post'>
                    <div id='registerPseudoDiv'>
                        <label  class='labelLOG'>Pseudo : </label>
                        <input id='registerPseudoInput' type='texte' name='registerPseudo' required minlength='5'
                            maxlength='15' class='textInput' pattern='^[^\s]+$'></input>
                    </div>
                    <div id='registerPasswordDiv'>
                        <label class='labelLOG'>Password : </label>
                        <input id='registerPasswordInput' type='password' name='registerPassword' required minlength='5'
                            maxlength='15' class='textInput' pattern='^[^\s]+$'></input>
                    </div>
                    <div id='registerCookieDiv'>
                        <label class='labelCookie'>Remember me :</label>
                        <input id='registerCookieInput' type='checkbox' name='registerCookie'></input>
                    </div>
                    <input type="submit" value="Register" class='submitButton'>
                </form>
            </div>
        </div>

        <div id='loginDiv' class='loginAndRegisterDiv'>
            <div id='loginContent'>
                <form id='loginForm' method='post'>
                    <div id='loginPseudoDiv'>
                        <label class='labelLOG'>Pseudo : </label>
                        <input id='loginPseudoInput' type='texte' name='loginPseudo' required maxlength='15'
                            class='textInput'></input>
                    </div>
                    <div id='loginPasswordDiv'>
                        <label class='labelLOG'>Password : </label>
                        <input id='loginPasswordInput' type='password' name='loginPassword' required minlength='5'
                            maxlength='15' class='textInput'></input>
                    </div>
                    <div id='loginCookieDiv'>
                        <label class='labelCookie'>Remember me :</label>
                        <input id='loginCookieInput' type='checkbox' name='loginCookie'></input>
                    </div>
                    <input type="submit" value="Login" class='submitButton'>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>