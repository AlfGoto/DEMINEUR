<?php

header("Cache-control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_start(); 

include('GlobalsVars.php');

$_SESSION['flagused'] = false;



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








if (isset($_COOKIE['pseudo'])) {
  $_SESSION['isLogged'] = true;
  $_SESSION['user'] = $_COOKIE['pseudo'];
}


#mute cookie
if (isset($_COOKIE['mute'])){
    echo "<script>var muteCookie = " . $_COOKIE['mute'] . ";</script>";
}

#Transfer the variables to JS
if (isset($_SESSION['isLogged'])) {
    if($_SESSION['isLogged']){
        echo '<script>var isLogged = ' . json_encode($_SESSION['isLogged']) . ';</script>';
        $sessionPseudo = $_SESSION['user'];
        echo "<script>var sessionPseudo = " . $sessionPseudo . ";</script>";
    }
}else{
    header('Location: ./loginRegister.php');
}


#Tables
try {
  $conn = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBPSEUDO, $DBCODE);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sqlA = "SELECT pseudo, time FROM times ORDER BY time ASC LIMIT 20";
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

  $sqlB = "SELECT pseudo, MIN(time) as best_time FROM times GROUP BY pseudo ORDER BY best_time ASC LIMIT 20";
  $stmtB = $conn->query($sqlB);

  $playerClassment = array();

  while ($rowb = $stmtB->fetch(PDO::FETCH_ASSOC)) {
    $playerClassment[] = array(
      'pseudo' => $rowb["pseudo"],
      'bestTime' => $rowb["best_time"]
    );
  }



  //Average win times in stats
  if(isset($_SESSION['user']) == true){
    $currentUser = $_SESSION['user'];

    $sqlC = "SELECT AVG(time) as average_time FROM times WHERE pseudo = :currentUser";
    $stmtC = $conn->prepare($sqlC);
    $stmtC->bindParam(':currentUser', $currentUser, PDO::PARAM_STR);
    $stmtC->execute();
    $rowc = $stmtC->fetch(PDO::FETCH_ASSOC);
    $averageTime = $rowc["average_time"];

    $updateSql = "UPDATE stats SET victoriesaverages = :averageTime WHERE pseudo = :currentUser";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':averageTime', $averageTime, PDO::PARAM_INT);
    $updateStmt->bindParam(':currentUser', $currentUser, PDO::PARAM_STR);
    $updateStmt->execute();


    //get the STATS in an array
    $sql = "SELECT * FROM stats WHERE pseudo = :currentUser";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':currentUser', $currentUser, PDO::PARAM_STR);
    $stmt->execute();

    $stats = array();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $stats['victories'] = $row['victories'];
      $stats['victoriesaverages'] = $row['victoriesaverages'];
      $stats['victoriesflagless'] = $row['victoriesflagless'];
      $stats['games'] = $row['games'];
      $stats['bombsExploded'] = $row['bombsExploded'];
      if($stats['games']>0){
        $statsWinrate = round(($stats['victories'] / $stats['games']) * 100) . '%';
      }else{
        $statsWinrate = 0;
      }
      
    }

  }
  



  $conn = null;
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}



#Get the classment and Bestscore of the Session player
if (isset($_SESSION['user'])) {
  for ($i = 0; $i < count($playerClassment); $i++) {
    if ($playerClassment[$i]['pseudo'] == $_SESSION['user']) {
      $_SESSION['rank'] = $i + 1;
      $_SESSION['bestTime'] = $playerClassment[$i]['bestTime'];
      break;
    }
  }
}



?>


<!DOCTYPE html>
<html>


<head>
    <script>
    //mute globalisation
    window.mute = false
    if(typeof muteCookie !== 'undefined'){
        if(muteCookie == true){window.mute = true}
        if(muteCookie == false){window.mute = false}
    }
    </script>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="./style.css" rel="stylesheet">
    </link>
    <script src='isLogged.js'></script>
    <script src='menu.js'></script>
    <div id='demineurScriptDiv'>
        <?php 
        include '.\MinesweeperEasy\buildMinesweeper.php'; 
        ?>
        <script src='.\MinesweeperEasy\scriptMinesweeper.js'></script>
    </div>

    <title>MineSweeper ULTIMATE</title>
    <link rel="icon" href="image\flagIcon.png" alt='icon of the website, its a redflag' />
    <h1 id='h1SEO'>Démineur Minesweeper</h1>
    <meta name="description"
        content="This is the ULTIMATE minesweeper game, lets see if you can be the best out of all players !">
    <meta name="author" content="AlfGoto">
    <meta name="title" content="Minesweeper">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="no-cache, must-revalidate, max-age=0">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <?php include "unlog.php" ?>
</head>


<body class="bodyMain">
    <div class="grid">
    </div>
    <div id="divInterfaceDemineur">
    </div>
    <div id="InterfaceEndGame">
        <div class="hidden" id="interfaceVictory">
            <p>Well done !</p>
            <p id='timerVictory'></p>
            <!-- <button class="button" type="button" id="victoryButton">Restart</button> -->
        </div>
        <div class="hidden" id="interfaceLose" class="visible">
            <p>The bombs exploded, better luck next time !</p>
            <!-- <button class="button" type="button" id="loseButton">Restart</button> -->
        </div>
    </div>


        



    <div id='menuDiv'>
        <div id='menuPetitDiv' class='closed hover'>
            <img alt='arrow left' id='arrowLeft' src='./image/arrow left.png'></img>
            <img alt='arrow right' id='arrowRight' src='./image/arrow right.png'></img>
            <button class="button" type="button" id="restartButton"></button>
            <div id="timerDemineur">
                <p></p>
            </div>
            <button class="button" type="button" id="fullscreenButton"><img src='./image/fullscreenLogo.png'
                    alt='button to set fullscreen on and off' id='fullscreenImg'></img></button>

            <button class="button" type="button" id="muteButton"><img src='./image/unMute.png'
                    alt='button to set a mute on and off' id='muteImg'></img></button>
        </div>

        <div id='menuLargeDiv'>
            <div>
                <div id='loggedInterface'>
                    <p>
                        Logged as
                        <?php echo $_SESSION['user'];
            if (isset($_SESSION['rank'])) {
              echo ', rank #' . $_SESSION['rank'];
              if (isset($_SESSION['rank'])) {
                echo '<br/>Best score : ' . $_SESSION['bestTime'] / 1000 . "s";
              }
            } ?>
                    </p>
                </div>
                <div id='statsDiv' class='tableDiv'>
                    <div id='tableStatsHaut'>
                        <h2 id='tableStatsTitle'>Statistics</h2>
                    </div>
                    <table id='statsTable'>
                        <thead>
                            <tr>
                                <th>Statistics</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class='tableStatsStatsNames'>Wins : </td>
                                <td>
                                    <?= $stats['victories'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='tableStatsStatsNames'>Average win time : </td>
                                <td>
                                    <?= $stats['victoriesaverages'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='tableStatsStatsNames'>Winrate : </td>
                                <td>
                                    <?= $statsWinrate ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='tableStatsStatsNames'>Number of games : </td>
                                <td>
                                    <?= $stats['games'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='tableStatsStatsNames'>Bombs exploded : </td>
                                <td>
                                    <?= $stats['bombsExploded'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class='tableStatsStatsNames'>Wins Flagless : </td>
                                <td>
                                    <?= $stats['victoriesflagless'] ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div id='tablePlayersDiv' class='tableDiv'>
                    <div id='tablePlayerHaut'>
                        <h2 id='tablePlayerTitle'>Best players</h2>
                    </div>
                    <table id='table'>
                        <tbody>
                            <tr>
                                <th id='tablePlayersClassment1'>1</th>
                                <td id='tablePlayersPseudo1'>
                                    <?= $playerClassment[0]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime1'>
                                    <?= $playerClassment[0]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment2'>2</th>
                                <td id='tablePlayersPseudo2'>
                                    <?= $playerClassment[1]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime2'>
                                    <?= $playerClassment[1]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment3'>3</th>
                                <td id='tablePlayersPseudo3'>
                                    <?= $playerClassment[2]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime3'>
                                    <?= $playerClassment[2]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment4'>4</th>
                                <td id='tablePlayersPseudo4'>
                                    <?= $playerClassment[3]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime4'>
                                    <?= $playerClassment[3]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment5'>5</th>
                                <td id='tablePlayersPseudo5'>
                                    <?= $playerClassment[4]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime5'>
                                    <?= $playerClassment[4]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment6'>6</th>
                                <td id='tablePlayersPseudo6'>
                                    <?= $playerClassment[5]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime6'>
                                    <?= $playerClassment[5]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment7'>7</th>
                                <td id='tablePlayersPseudo7'>
                                    <?= $playerClassment[6]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime7'>
                                    <?= $playerClassment[6]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment8'>8</th>
                                <td id='tablePlayersPseudo8'>
                                    <?= $playerClassment[7]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime8'>
                                    <?= $playerClassment[7]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment9'>9</th>
                                <td id='tablePlayersPseudo9'>
                                    <?= $playerClassment[8]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime9'>
                                    <?= $playerClassment[8]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tablePlayersClassment10'>10</th>
                                <td id='tablePlayersPseudo10'>
                                    <?= $playerClassment[9]['pseudo'] ?>
                                </td>
                                <td id='tablePlayersTime10'>
                                    <?= $playerClassment[9]['bestTime'] / 1000 ?>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <div id='tableDiv' class='tableDiv'>
                    <div id='tableHaut'>
                        <h2 id='tableTitle'>Best times</h2>
                    </div>
                    <table id='table'>
                        <tbody>
                            <tr>
                                <th id='tableClassment1'>1</th>
                                <td id='tablePseudo1'>
                                    <?= $tablePseudo[0] ?>
                                </td>
                                <td id='tableTime1'>
                                    <?= $tableTime[0] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment2'>2</th>
                                <td id='tablePseudo2'>
                                    <?= $tablePseudo[1] ?>
                                </td>
                                <td id='tableTime2'>
                                    <?= $tableTime[1] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment3'>3</th>
                                <td id='tablePseudo3'>
                                    <?= $tablePseudo[2] ?>
                                </td>
                                <td id='tableTime3'>
                                    <?= $tableTime[2] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment4'>4</th>
                                <td id='tablePseudo4'>
                                    <?= $tablePseudo[3] ?>
                                </td>
                                <td id='tableTime4'>
                                    <?= $tableTime[3] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment5'>5</th>
                                <td id='tablePseudo5'>
                                    <?= $tablePseudo[4] ?>
                                </td>
                                <td id='tableTime5'>
                                    <?= $tableTime[4] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment6'>6</th>
                                <td id='tablePseudo6'>
                                    <?= $tablePseudo[5] ?>
                                </td>
                                <td id='tableTime6'>
                                    <?= $tableTime[5] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment7'>7</th>
                                <td id='tablePseudo7'><?= $tablePseudo[6] ?></td>
                                <td id='tableTime7'><?= $tableTime[6] / 1000 ?></td>
                            </tr>
                            <tr>
                                <th id='tableClassment8'>8</th>
                                <td id='tablePseudo8'><?= $tablePseudo[7] ?>
                                </td>
                                <td id='tableTime8'>
                                    <?= $tableTime[7] / 1000 ?>
                                </td>

                            </tr>
                            <tr>
                                <th id='tableClassment9'>9</th>
                                <td id='tablePseudo9'>
                                    <?= $tablePseudo[8] ?>
                                </td>
                                <td id='tableTime9'>
                                    <?= $tableTime[8] / 1000 ?>
                                </td>
                            </tr>
                            <tr>
                                <th id='tableClassment10'>10</th>
                                <td id='tablePseudo10'>
                                    <?= $tablePseudo[9] ?>
                                </td>
                                <td id='tableTime10'>
                                    <?= $tableTime[9] / 1000 ?>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                
                <div id='unlogButtonDiv' class='visible'>
                    <form action="index.php" method="post">
                        <button class='button' name='unlogButton' type="submit" value='Unlog'
                            id="unlogButton">Unlog</button>
                    </form>
                </div>
            </div>
        </div>
        <a href="https://discord.gg/xGMJFVaHUR"><img id='discord' src='./image/discord logo.png' target='_blanck'
                alt='le logo de discord, clickable pour acceder a mon serveur'></a>
    </div>








</body>


</html>