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
    }else{
      $request = 'INSERT INTO login(pseudo, password) VALUES(:pseudo, :password)';
      $sql = $db->prepare($request);
      $sql->execute([
        'pseudo'=>$registerPseudo,
        'password'=>$registerPassword
      ]);
      echo 'your account has been created';
      $is_logged = true;
      $_SESSION['pseudo'] = $registerPseudo;
      echo "welcome $registerPseudo";
    }

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
        $_SESSION['pseudo'] = $loginPseudo;
        echo "welcome $loginPseudo";
      }else{
        echo 'Wrong password';
      }
    }else{echo ('le compte n\'existe pas');}
  }
};


?>