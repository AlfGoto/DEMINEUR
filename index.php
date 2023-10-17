<?php

session_start();


header("Cache-control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");


$is_logged = false;

if(isset($_COOKIE['pseudo']) == true){
  $_SESSION['isLogged'] = true;
  $_SESSION['user'] = $_COOKIE['pseudo'];
  $is_logged = true;
}else{
  if(isset($_SESSION['user']) == true){
    $is_logged = true;
  }
}

#Transfer the variables to JS
echo "<script>var isLogged = '$is_logged';</script>";
if(isset($_SESSION['user']) == true){
  $sessionPseudo = $_SESSION['user'];
  echo "<script>var sessionPseudo = '$sessionPseudo';</script>";
}



try {
  $conn = new PDO("mysql:host=localhost;dbname=minesweeper", 'root', 'root');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sqlA = "SELECT pseudo, time FROM times ORDER BY time ASC LIMIT 10";
  $stmtA = $conn->query($sqlA);

  $seenTimes = array(); 
  $tablePseudo = array();
  $tableTime = array();

  while ($rowa = $stmtA->fetch(PDO::FETCH_ASSOC)) {
    $pseudo = $rowa["pseudo"];
    $time = $rowa["time"];
    
    if (!isset($seenTimes[$time])) {
        $tablePseudo[] = $pseudo;
        $tableTime[] = $time;
        $seenTimes[$time] = true; 
    }
}

    $sqlB = "SELECT pseudo, MIN(time) as best_time FROM times GROUP BY pseudo ORDER BY best_time ASC LIMIT 10";
    $stmtB = $conn->query($sqlB);

    $playerClassment = array();

    // Fetch data and store them in an associative array
    while ($rowb = $stmtB->fetch(PDO::FETCH_ASSOC)) {
        $playerClassment[] = array(
            'pseudo' => $rowb["pseudo"],
            'bestTime' => $rowb["best_time"]
        );
      }
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

#Get the classment of the Session player
if($is_logged = true){
  for($i = 0; $i < count($playerClassment); $i++){
    if($playerClassment[$i]['pseudo'] == $_SESSION['user']){
      $_SESSION['rank'] = $i+1;
      break;
    }
  }
}



?>


<!DOCTYPE html>
<html>

<script src='loginInterface.js'></script>

<head>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<div id='registerDiv' class='loginAndRegisterDiv registerDivOpen'>
  <h2 class='notSelectable' id='registerTitle'>Create your account</h2>
  <div id='registerContent'>
    <form id='registerForm' method='post'>
    <div id='registerPseudoDiv'>
      <label id='registerPseudoLabel'>Pseudo : </label>
      <input id='registerPseudoInput' type='texte' name='registerPseudo' required minlength='5' maxlength='10' class='textInput'></input>
    </div>
    <div id='registerPasswordDiv'>
      <label id='registerPasswordLabel'>Password : </label>
      <input id='registerPasswordInput' type='password' name='registerPassword' required minlength='5' maxlength='10' class='textInput'></input>
      <p class='notSelectable'>don't put your usual password, <br/> i'm still working on making this site ultra safe</p>
    </div>
    <div id='registerCookieDiv'>
      <label id='registerCookieLabel'>Remember this computer for a year ?</label>
      <input id='registerCookieInput' type='checkbox' name='registerCookie'></input>
    </div>
    <input type="submit" value="Register" class='submitButton'>
  </form>
</div>
 </div>

 <div id='loginDiv' class='loginAndRegisterDiv loginDivOpen'>
  <h2 class='notSelectable' id='loginTitle'>Login</h2>
  <div id='loginContent'>
    <form id='loginForm' method='post'>
    <div id='loginPseudoDiv'>
      <label id='loginPseudoLabel'>Pseudo : </label>
      <input id='loginPseudoInput' type='texte' name='loginPseudo' required maxlength='10' class='textInput'></input>
    </div>
    <div id='loginPasswordDiv'>
      <label id='loginPasswordLabel'>Password : </label>
      <input id='loginPasswordInput' type='password' name='loginPassword' required minlength='5' maxlength='10' class='textInput'></input>
    </div>
    <div id='loginCookieDiv'>
      <label id='loginCookieLabel'>Remember this computer for a year?</label>
      <input id='loginCookieInput' type='checkbox' name='loginCookie'></input>
    </div>
    <input type="submit" value="Login" class='submitButton'>
  </form>
</div>
 </div>

 <?php
 include "loginRegister.php"
 ?>
</div>

<div id='loggedInterface'>
  <p>Logged as <?php echo $_SESSION['user'];
  if(isset($_SESSION['rank'])){
    echo ', rank #'.$_SESSION['rank'];
  }
  ?></p>
</div>



<div id='tableDiv'>
  <table id='table'>
    <thead>
      <tr>
        <th id='tableTitreClassment'>Classment</th>
        <td id='tableTitrePseudo'>Pseudo</td>
        <td id='tableTitreTime'>Time</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th id='tableClassment1'>1</th>
        <td id='tablePseudo1'><?= $tablePseudo[0]?></td>
        <td id='tableTime1'><?= $tableTime[0]/1000?></td>
      </tr>
        <th id='tableClassment2'>2</th>
        <td id='tablePseudo2'><?= $tablePseudo[1]?></td>
        <td id='tableTime2'><?= $tableTime[1]/1000?></td>
      </tr>
        <th id='tableClassment3'>3</th>
        <td id='tablePseudo3'><?= $tablePseudo[2]?></td>
        <td id='tableTime3'><?= $tableTime[2]/1000?></td>
      </tr>
        <th id='tableClassment4'>4</th>
        <td id='tablePseudo4'><?= $tablePseudo[3]?></td>
        <td id='tableTime4'><?= $tableTime[3]/1000?></td>
      </tr>
        <th id='tableClassment5'>5</th>
        <td id='tablePseudo5'><?= $tablePseudo[4]?></td>
        <td id='tableTime5'><?= $tableTime[4]/1000?></td>
      </tr>
      <tr>
        <th id='tableClassment6'>6</th>
        <td id='tablePseudo6'><?= $tablePseudo[5]?></td>
        <td id='tableTime6'><?= $tableTime[5]/1000?></td>
      </tr>
        <th id='tableClassment7'>7</th>
        <td id='tablePseudo7'><?= $tablePseudo[6]?></td>
        <td id='tableTime7'><?= $tableTime[6]/1000?></td>
      </tr>
        <th id='tableClassment8'>8</th>
        <td id='tablePseudo8'><?= $tablePseudo[7]?></td>
        <td id='tableTime8'><?= $tableTime[7]/1000?></td>
      </tr>
        <th id='tableClassment9'>9</th>
        <td id='tablePseudo9'><?= $tablePseudo[8]?></td>
        <td id='tableTime9'><?= $tableTime[8]/1000?></td>
      </tr>
        <th id='tableClassment10'>10</th>
        <td id='tablePseudo10'><?= $tablePseudo[9]?></td>
        <td id='tableTime10'><?= $tableTime[9]/1000?></td>
      </tr>
    </tbody>

  </table>
</div>

<div id='tablePlayersDiv'>
  <table id='table'>
    <thead>
      <tr>
        <th id='tablePlayersTitreClassment'>Classment</th>
        <td id='tablePlayersTitrePseudo'>Pseudo</td>
        <td id='tablePlayersTitreTime'>Time</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th id='tablePlayersClassment1'>1</th>
        <td id='tablePlayersPseudo1'><?= $playerClassment[0]['pseudo']?></td>
        <td id='tablePlayersTime1'><?= $playerClassment[0]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment2'>2</th>
        <td id='tablePlayersPseudo2'><?= $playerClassment[1]['pseudo']?></td>
        <td id='tablePlayersTime2'><?= $playerClassment[1]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment3'>3</th>
        <td id='tablePlayersPseudo3'><?= $playerClassment[2]['pseudo']?></td>
        <td id='tablePlayersTime3'><?= $playerClassment[2]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment4'>4</th>
        <td id='tablePlayersPseudo4'><?= $playerClassment[3]['pseudo']?></td>
        <td id='tablePlayersTime4'><?= $playerClassment[3]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment5'>5</th>
        <td id='tablePlayersPseudo5'><?= $playerClassment[4]['pseudo']?></td>
        <td id='tablePlayersTime5'><?= $playerClassment[4]['bestTime']/1000?></td>
      </tr>
      <tr>
        <th id='tablePlayersClassment6'>6</th>
        <td id='tablePlayersPseudo6'><?= $playerClassment[5]['pseudo']?></td>
        <td id='tablePlayersTime6'><?= $playerClassment[5]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment7'>7</th>
        <td id='tablePlayersPseudo7'><?= $playerClassment[6]['pseudo']?></td>
        <td id='tablePlayersTime7'><?= $playerClassment[6]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment8'>8</th>
        <td id='tablePlayersPseudo8'><?= $playerClassment[7]['pseudo']?></td>
        <td id='tablePlayersTime8'><?= $playerClassment[7]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment9'>9</th>
        <td id='tablePlayersPseudo9'><?= $playerClassment[8]['pseudo']?></td>
        <td id='tablePlayersTime9'><?= $playerClassment[8]['bestTime']/1000?></td>
      </tr>
        <th id='tablePlayersClassment10'>10</th>
        <td id='tablePlayersPseudo10'><?= $playerClassment[9]['pseudo']?></td>
        <td id='tablePlayersTime10'><?= $playerClassment[9]['bestTime']/1000?></td>
      </tr>
    </tbody>

  </table>
</div>


</body>


</html>
