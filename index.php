<?php

$is_logged = false;

?>

<!DOCTYPE html>
<html>

<head>
  <link href="./style.css" rel="stylesheet"></link>
  <script src="./scriptDemineur.js" id="demineurScript"></script>
</head>


<body class="bodyMain">
 <div class="grid">
 </div>
 <div id="divInterfaceDemineur">
  <button class="button" type="button" id="theBigOne">Restart</button>
 </div>
 <div class="hidden" id="timerDemineur">
  <p></p>
 </div>
 <div id="InterfaceEndGame">
  <div class="hidden" id="interfaceVictory">
    <p>Victoire !</p>
    <p id='timerVictory'></p>
    <button class="button" type="button" id="victoryButton">Restart</button>
  </div>
  <div class="hidden" id="interfaceLose" class="visible">
    <p>Tu as fait sauter une bombe</p>
    <button class="button" type="button" id="loseButton">Restart</button>
  </div>
 </div>


<div id='loginRegisterInterface'>
 <div id='loginDiv' class='loginAndRegisterDiv'>
 <h2 id='loginTitle'>Login</h2>
  <form id='loginForm' method='post'>
    <div id='loginPseudoDiv'>
      <label id='loginPseudoLabel'>Pseudo</label>
      <input id='loginPseudoInput' type='texte' name='loginPseudo' required maxlength='10'></input>
    </div>
    <div id='loginPasswordDiv'>
      <label id='loginPasswordLabel'>Password</label>
      <input id='loginPasswordInput' type='password' name='loginPassword' required minlength='5' maxlength='10'></input>
    </div>
    <div id='loginCookieDiv'>
      <label id='loginCookieLabel'>Remember this computer for a year?</label>
      <input id='loginCookieInput' type='checkbox' name='loginCookie'></input>
    </div>
    <input type="submit" value="Submit">
  </form>
  <p id='goToRegister'>Register</p>
 </div>
 
 <div id='registerDiv' class='loginAndRegisterDiv'>
  <h2 id='registerTitle'>Register</h2>
  <form id='registerForm' method='post'>
    <div id='registerPseudoDiv'>
      <label id='registerPseudoLabel'>Pseudo</label>
      <input id='registerPseudoInput' type='texte' name='registerPseudo' required minlength='5' maxlength='10'></input>
    </div>
    <div id='registerPasswordDiv'>
      <label id='registerPasswordLabel'>Password</label>
      <input id='registerPasswordInput' type='password' name='registerPassword' required minlength='5' maxlength='10'></input>
      <p>don't put your usual password, <br/> i'm still working on making this site ultra safe</p>
    </div>
    <div id='registerCookieDiv'>
      <label id='registerCookieLabel'>Remember this computer for a year ?</label>
      <input id='registerCookieInput' type='checkbox' name='registerCookie'></input>
    </div>
    <input type="submit" value="Submit">
  </form>
  <p id='goToLogin'>Login</p>
 </div>
</div>


</body>




</html>



<?php

#Connecting to the database
try{$db = new PDO('mysql:host=localhost;dbname=minesweeper;charset=utf8',
  'root',
  'root',
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);}
catch(Exception $e){
  die('erreur : '. $e->getMessage());
}


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
    }else{echo ('le nom est dispo');}

  }

  #LOGIN
  if(isset($_POST['loginPseudo'])){
    $loginPseudo = htmlspecialchars(strip_tags($_POST['loginPseudo']));
    $loginPassword = htmlspecialchars(strip_tags($_POST['loginPassword']));
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
        $is_logged = true;
        $_SESSION['name'] = $loginPseudo;
        echo "welcome $loginPseudo";
      }else{
        echo 'Wrong password';
      }
    }else{echo ('le compte n\'existe pas');}
  }
};


$request = "SELECT password FROM login WHERE pseudo=:pseudo";
$sql = $db->prepare($request);
$sql->bindParam(":pseudo", $loginPseudo);
$sql->execute();
$result = $sql->fetch(PDO::FETCH_ASSOC);


?>