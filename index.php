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
    <p>Tu as fais sauter une bombe</p>
    <button class="button" type="button" id="loseButton">Restart</button>
  </div>
 </div>
</body>



</html>