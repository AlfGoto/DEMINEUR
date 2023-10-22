document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('.grid');
    let width = 20
    let bombAmount = 10
    let squares = []
    let isGameOver = false
    let flags = 0
    let firstSquare = true
    let total = 0
    let secondSquare = false
    let currentIndex = 0
    
    
    
    
    let squaresArray = []
    



    //Sound setup
    function flagSound() {
        if(mute == true){
            return
        }else{
            let flagsoundvar = new Audio('./son/flag.mp3')
            flagsoundvar.play();
        }
    }
    let dirt = 0
    function checkedSound(){
        if(mute == true){
            return
        }else{
            if(dirt == 0){
                let dirts = new Audio('./son/dirt0.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 1){
                let dirts = new Audio('./son/dirt1.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 2){
                let dirts = new Audio('./son/dirt2.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 3){
                let dirts = new Audio('./son/dirt3.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 4){
                let dirts = new Audio('./son/dirt4.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 5){
                let dirts = new Audio('./son/dirt5.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 6){
                let dirts = new Audio('./son/dirt6.mp3')
                dirts.play()
                dirt++
                return
            }
            if(dirt == 7){
                let dirts = new Audio('./son/dirt7.mp3')
                dirts.play()
                dirt = 0
                return
            }
        }
    }



    //timmer declaration
    let timer;
    let startTime;
    let elapsedTime = 0;
    let isRunning = false;
    let timerHTML = document.getElementById('timerDemineur')
    timerHTML.innerHTML = 'Timer'
    let now;

    //create board
    function createBoard() {
        //met les bombes
        const bombsArray = Array(bombAmount).fill('bomb')
        const emptyArray = Array(width*width - bombAmount).fill('valid')
        const gameArray = emptyArray.concat(bombsArray)
        const shuffle = array => {
            for (let k = array.length - 1; k > 0; k--) {
              const l = Math.floor(Math.random() * (k + 1));
              const temp = array[k];
              array[k] = array[l];
              array[l] = temp;
            }
            return array
          }
        const shuffledArray = shuffle(gameArray)


        //this is for the cool animations
        for (let i = 0; i < width*width; i++) {
            squaresArray.push(i);
        }
        shuffle(squaresArray)
        console.log(squaresArray)


        firstSquare = true
        secondSquare = false
        currentIndex = 0

        //create squares
        for(let i = 0; i < width*width; i++) {
            const square = document.createElement('div');
            square.setAttribute('id', i)
            square.classList.add(shuffledArray[i])
            grid.appendChild(square);
            squares.push(square);


            //squares grid
            if(i<20 || (i>39 && i<60) || (i>39 && i<60) || (i>79 && i<100) || (i>119 && i<140) || (i>159 && i<180) || (i>199 && i<220) || (i>239 && i<260) || (i>279 && i<300)|| (i>319 && i<340) || (i>359 && i<380)){
                if(i%2 == 0){
                    square.classList.add('green')
                }else{
                    square.classList.add('lightGreen')
                }
            }
            if((i>19 && i<40) || (i>59 && i<80) || (i>19 && i<40) || (i>99 && i<120)|| (i>139 && i<160) || (i>179 && i<200) || (i>219 && i<240)|| (i>259 && i<280) || (i>299 && i<320) || (i>339 && i<360) || (i>379 && i<400)){
                if(i%2 == 0){
                    square.classList.add('lightGreen')
                }else{
                    square.classList.add('green')
                }
            }
        





            //normal click
            square.addEventListener('mousedown', function(e) {
                if (e.button === 0 ){
                    if(!(square.classList.contains('checked'))){
                        checkedSound()
                    }
                    click(square)
                }
                
            })

            //right click
            square.addEventListener('mousedown', function(e) {
                if (e.button === 2 ){
                    e.preventDefault()
                    addFlag(square)
                }
                
            })

            //left and right click
            let rightClick = false
            let leftClick = false
            square.addEventListener('mousedown', function(f) {
                if (f.button === 2){
                    rightClick = true
                }
                if (f.button === 0){
                    leftClick = true
                }
                if ((leftClick == true) && (rightClick == true)){
                    leftRightClick(i, square)
                }
            })
            square.addEventListener('mouseup', function(g){
                if (g.button === 2){
                    rightClick = false
                }
                if (g.button === 0){
                    leftClick = false
                }
            })


            //cntrl and left click
            square.oncontextmenu = function(e) {
                e.preventDefault()
            }
        }

        //numbers on square
        for (let i = 0; i < squares.length; i++) {
            total = 0
            const isLeftEdge = (i % width === 0)
            const isRightEdge = (i % width === width -1)

            if (squares[i].classList.contains('valid')) {
                if (i > 0 && !isLeftEdge && squares[i -1].classList.contains('bomb')) total++
                if (i > 19 && !isRightEdge && squares[i +1 -width].classList.contains('bomb')) total++
                if (i > 20 && squares[i - width].classList.contains('bomb')) total++
                if (i > 21 && !isLeftEdge && squares[i  -1 -width].classList.contains('bomb')) total++
                if (i < 398 && !isRightEdge && squares[i  +1].classList.contains('bomb')) total++
                if (i < 380 && !isLeftEdge && squares[i  -1 +width].classList.contains('bomb')) total++
                if (i < 378 && !isRightEdge && squares[i  +1 +width].classList.contains('bomb')) total++
                if (i < 379 && squares[i  +width].classList.contains('bomb')) total++
                if (i === 398 && squares[i  +1].classList.contains('bomb')) total++
                if (i === 379 && squares[i  +20].classList.contains('bomb')) total++
                if (i === 378 && squares[i  +21].classList.contains('bomb')) total++
                if (i === 21 && squares[i  -21].classList.contains('bomb')) total++
                if (i === 20 && squares[i  -20].classList.contains('bomb')) total++
                squares[i].setAttribute('data', total)

                //differents colors for the numbers
                switch(total){
                    case 1:
                        squares[i].classList.add('data1')
                        break;
                    case 2:
                        squares[i].classList.add('data2')
                        break;
                    case 3:
                        squares[i].classList.add('data3')
                        break;
                    case 4:
                        squares[i].classList.add('data4')
                        break;
                    case 5:
                        squares[i].classList.add('data5')
                        break;
                    case 6:
                        squares[i].classList.add('data6')
                        break;
                    case 7:
                        squares[i].classList.add('data7')
                        break;
                    case 8:
                        squares[i].classList.add('data8')
                        break;

                }
            }
        }





    }
    createBoard()

    //add flags with right click
    function addFlag(square) {
        if (isGameOver) return
        if (!square.classList.contains('checked') && (flags < bombAmount)) {
            if (!square.classList.contains('flag')) {
                square.classList.add('flag')
                square.innerHTML = `<img class='imageFlag' src='image/flag.png'></img>`
                flags++
                flagSound()
                if(flags < 2){
                    console.log('this is not a flagless run anymore')
                    $.ajax({
                        type: "POST", 
                        url: "JStoPHP.php",
                        data: { 
                            flagused: true
                        },
                    })
                }
            } else {
                square.classList.remove('flag')
                square.innerHTML = ' '
                flags --
            }
        }
    }


    //left and right Click
    function leftRightClick(i, square){
        checkedSound()
        let bombAround = square.getAttribute('data')
        let totalFlags = 0
        const isLeftEdge = (i % width === 0)
        const isRightEdge = (i % width === width -1)
        if (bombAround > 0) {
            if (i > 0 && !isLeftEdge && squares[i -1].classList.contains('flag')) totalFlags++
            if (i > 19 && !isRightEdge && squares[i +1 -width].classList.contains('flag')) totalFlags++
            if (i > 20 && squares[i - width].classList.contains('flag')) totalFlags++
            if (i > 21 && !isLeftEdge && squares[i  -1 -width].classList.contains('flag')) totalFlags++
            if (i < 398 && !isRightEdge && squares[i  +1].classList.contains('flag')) totalFlags++
            if (i < 380 && !isLeftEdge && squares[i  -1 +width].classList.contains('flag')) totalFlags++
            if (i < 378 && !isRightEdge && squares[i  +1 +width].classList.contains('flag')) totalFlags++
            if (i < 379 && squares[i  +width].classList.contains('flag')) totalFlags++
            if (i === 398 && squares[i  +1].classList.contains('flag')) totalFlags++
            if (i === 379 && squares[i  +20].classList.contains('flag')) totalFlags++
            if (i === 378 && squares[i  +21].classList.contains('flag')) totalFlags++
            if (totalFlags == bombAround){
                if(isLeftEdge){
                    if(isGameOver == false){
                        click(squares[i +1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i - width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +width])
                    }
                }else if(isRightEdge){
                    if(isGameOver == false){
                        click(squares[i -1])
                    }
                    if(isGameOver == false){
                        click(squares[i - width])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +width])
                    }
                }else if(i<20){
                    if(isGameOver == false){
                        click(squares[i -1])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +width])
                    }
                }else if(i<400 && i>379){
                    if(isGameOver == false){
                        click(squares[i -1])
                    }
                    if(isGameOver == false){
                        click(squares[i +1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i - width])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1])
                    }   
                }else if(i == 0){
                    if(isGameOver == false){
                        click(squares[i  +1])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +width])
                    }   
                }else{
                    if(isGameOver == false){
                        click(squares[i -1])
                    }
                    if(isGameOver == false){
                        click(squares[i +1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i - width])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1])
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width])
                    }
                    if(isGameOver == false){
                        click(squares[i  +width])
                    }                    
                }
                
            }
        }
    }
    

    //click on square action
    function click(square){
        let currentId = square.id
        let total = square.getAttribute('data')
        if (firstSquare === true) {
            if (square.classList.contains('bomb')){
                restart()
                let squareRestart = document.getElementById(currentId)
                click(squareRestart)
                return
            }else if(total !=0){
                restart()
                let squareRestart = document.getElementById(currentId)
                click(squareRestart)
            } 
        }
        if(firstSquare === true){
            firstSquare = false
            secondSquare = true
            startTimer()
        }
        if(secondSquare == true){
            secondSquare = false
            $.ajax({
                type: "POST", 
                url: "JStoPHP.php",
                data: { 
                    game: true
                },
                success: function(response) {
                    console.log("Data sent successfully!" + "Response from server: " + response);
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: " + error);
                }
            })
        }
        if (isGameOver) return
        if (square.classList.contains('checked') || square.classList.contains('flag')) return
        if (square.classList.contains('bomb')) {
            console.log('UNE BOMBE A EXPLOSE')
            let loseInterface = document.getElementById('interfaceLose')
            loseInterface.classList.remove('hidden')
            loseInterface.classList.add('visible')
            isGameOver = true
            $.ajax({
                type: "POST", 
                url: "JStoPHP.php",
                data: { 
                    elapsedTime: elapsedTime,
                    bomb: true
                },
                success: function(response) {
                    console.log("Data sent successfully!" + "Response from server: " + response);
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: " + error);
                }
            })
            stopTimer()
        }else {
            if (total !=0) {
                square.classList.add('checked')
                if(square.classList.contains('green')){
                    square.classList.remove('green')
                    square.classList.add('gray')
                }else{
                    square.classList.remove('lightGreen')
                    square.classList.add('silver')
                }
                square.innerHTML = total
                if(isGameOver == false){checkForWin()}
                
                return
            }else{if(isGameOver == false){checkForWin()}}
            checkSquare(square, currentId) 
            if(square.classList.contains('green')){
                square.classList.remove('green')
                square.classList.add('gray')
            }else{
                square.classList.remove('lightGreen')
                square.classList.add('silver')
            }
        }
        square.classList.add('checked')
        if(firstSquare === true){
            startTimer()
        }
        
    }



    //check neighbourg squares
    function checkSquare(square, currentId) {
        const isLeftEdge = (currentId % width === 0)
        const isRightEdge = (currentId % width === width -1)

        setTimeout(() => {
            if(currentId >0 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId > 19 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1 -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId > 20) {
                const newId = squares[parseInt(currentId) -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId > 21 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1 -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId <= 399 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId < 380 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1 +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId < 378 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1 +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if (currentId < 379) {
                const newId = squares[parseInt(currentId) +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId == 398){
                const newId = squares[parseInt(currentId) +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId == 379){
                const newId = squares[parseInt(currentId) +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId == 378){
                const newId = squares[parseInt(currentId) +width +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId == 20){
                const newId = squares[parseInt(currentId) -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }
            if(currentId == 21){
                const newId = squares[parseInt(currentId) -width -1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            }

        }, 10)
    }














    //check for win
    function checkForWin() {
        let matches = 0
        
        for (let i = 0; i < squares.length;i++) {
            if (squares[i].classList.contains('checked')) {
                matches++
            }
            if (matches === (width*width-bombAmount) && isGameOver == false) {
                isGameOver = true
                stopTimer()

                //timer in victory interface
                let timerVictory = document.getElementById('timerVictory')
                elapsedTime = now - startTime;
                const seconds = Math.floor(elapsedTime / 1000);
                const milliseconds = elapsedTime % 1000;
                timerVictory.innerHTML = seconds + '.' + milliseconds

                //send elapsedTime to PHP
                if(bombAmount > 69){
                    $.ajax({
                        type: "POST", 
                        url: "JStoPHP.php",
                        data: { 
                            elapsedTime: elapsedTime,
                            victory: true
                        },
                        success: function(response) {
                            console.log("Data sent successfully!" + "Response from server: " + response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred: " + error);
                        }
                    })
                }
                animVictory()
            }
        }
        
    }








    //restart
    function restart(){
        grid.innerHTML = ''
        flags = 0
        squares.length = 0
        isGameOver = false
        stopTimer()
        resetTimer()
        createBoard()
        let victoryInterface = document.getElementById('interfaceVictory')
        victoryInterface.classList.remove('visible')
        victoryInterface.classList.add('hidden')
        let loseInterface = document.getElementById('interfaceLose')
        loseInterface.classList.remove('visible')
        loseInterface.classList.add('hidden')
    }
    
    const restartButton = document.getElementById('restartButton')
    restartButton.addEventListener("click", ()=>{restart()})

        //timer
    function startTimer() {
        if (!isRunning) {
            isRunning = true;
            startTime = Date.now() - elapsedTime;
            timer = setInterval(function() {
                now = Date.now();
                elapsedTime = now - startTime;
                const seconds = Math.floor(elapsedTime / 1000);
                const milliseconds = elapsedTime % 1000;
                timerHTML.innerHTML = seconds + '.' + Math.floor(milliseconds/10)
            })
        }
    }
    
    function stopTimer() {
        if (isRunning) {
            isRunning = false;
            clearInterval(timer);
            timerHTML.innerHTML = 'Timer'
        }
    }
    function resetTimer() {
        clearInterval(timer);
        isRunning = false;
        elapsedTime = 0;
        console.log("Timer reset");
    }



    

    function animVictoryLoop() {
        let square = document.getElementById(squaresArray[currentIndex]);
        console.log('victory LOOP ' + currentIndex + '  square nb-'+square.id)
        square.innerHTML =' '
        square.style.animation = 'fadeIn 2s';
        if (square.classList.contains('gray')) {
            square.classList.remove('gray');
            square.classList.add('green');
        } 
        else if(square.classList.contains('green')){
        } 
        else if(square.classList.contains('lightGreen')){
        }
        else if(square.classList.contains('silver')){
            square.classList.remove('silver');
            square.classList.add('lightGreen');
        }
        currentIndex++;
        if (currentIndex < width*width) {
            setTimeout(animVictoryLoop, 25); 
        } else {
            let victoryInterface = document.getElementById('interfaceVictory')
            victoryInterface.style.animation = 'fadeIn 2s';
            victoryInterface.classList.remove('hidden')
            victoryInterface.classList.add('visible')
        }
    }


    function animVictory(){
        console.log('anim victory en cours')
        animVictoryLoop()
    }
    
})