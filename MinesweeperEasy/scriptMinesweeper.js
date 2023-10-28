document.addEventListener('DOMContentLoaded', () => {



    //Vars
    const grid = document.querySelector('.grid');
    let width = 20
    let squares = []
    let isGameOver = false
    let flags = 0
    


    //PHP Vars







    //SOUND
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

    function bombSound(){
        if(mute == true){return}
        if(isGameOver == false){return}
        function getRandomInt(max) {
            return Math.floor(Math.random() * max);
          }
        let bombSoun;
        switch(getRandomInt(6)){
            case 0:
                bombSoun = new Audio('./son/bomb0.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
            case 1:
                bombSoun = new Audio('./son/bomb1.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
            case 2:
                bombSoun = new Audio('./son/bomb2.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
            case 3:
                bombSoun = new Audio('./son/bomb3.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
            case 4:
                bombSoun = new Audio('./son/bomb4.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
            case 5:
                bombSoun = new Audio('./son/bomb5.mp3');
                bombSoun.volume = 0.25;
                bombSoun.play();
                break;
        }
    }

    //BUILD
    //create squares
    function build(){
        for(let i = 0; i < width*width; i++) {
            const square = document.createElement('div');
            square.setAttribute('id', i)
            grid.appendChild(square);
            squares.push(square)
    
    
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
    
            //clicks
            //normal click
            square.addEventListener('mousedown', function(e) {
                if (e.button === 0 ){
                    click(square, i)
                }
            
            })
    
            //right click
            square.addEventListener('mousedown', function(e) {
                if (e.button === 2 ){
                    e.preventDefault()
                    addFlag(square)
                }
                
            })
    
            //no context menu
            square.oncontextmenu = function(e) {
                e.preventDefault()
            }
    
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
                    lightLeftRightClick(i, square)
                }
            })
            square.addEventListener('mouseup', function(g){
                if (g.button === 2){
                    rightClick = false
                }
                if (g.button === 0){
                    leftClick = false
                }
                if ((leftClick == false) && (rightClick == false) && (lightOn == true)){
                    lightLeftRightClickOFF()
                }
            })
    
    
    
    
    
        }
    }
    build()

     


    //left click
    function click(square, i){
        if(square.classList.contains('checked')){return}
        if(square.classList.contains('flag')){return}
        $.ajax({
            type: "POST", 
            url: "./MinesweeperEasy/requests.php",
            data: {
                request: 'click',
                idSquare: square.getAttribute('id')
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.isBomb) {
                    alert('BOMB')
                    return;
                } else {
                    if(square.classList.contains('green')){
                        square.classList.remove('green')
                        square.classList.add('gray')
                    }else if(square.classList.contains('lightGreen')){
                        square.classList.remove('lightGreen')
                        square.classList.add('silver')
                    }
                }
                square.classList.add('checked')
                if (result.data != 0){
                    square.innerHTML = result.data
                    square.setAttribute('data', result.data)
                    
                    //differents colors for the numbers
                    switch(result.data){
                        case 1:
                            square.classList.add('data1')
                            break;
                        case 2:
                            square.classList.add('data2')
                            break;
                        case 3:
                            square.classList.add('data3')
                            break;
                        case 4:
                            square.classList.add('data4')
                            break;
                        case 5:
                            square.classList.add('data5')
                            break;
                        case 6:
                            square.classList.add('data6')
                            break;
                        case 7:
                            square.classList.add('data7')
                            break;
                        case 8:
                            square.classList.add('data8')
                            break;

                    }
                    return
                } 
                if (result.data == 0){
                    checkSquare(square, i)
                }





            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX: " + error);
            }
        })
    }










    //check neighbourg squares
    function checkSquare(square, currentId) {
        const isLeftEdge = (currentId % width === 0)
        const isRightEdge = (currentId % width === width -1)

        setTimeout(() => {
            if(currentId >0 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId > 19 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1 -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId > 20) {
                const newId = squares[parseInt(currentId) -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId > 21 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1 -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId <= 399 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId < 380 && !isLeftEdge) {
                const newId = squares[parseInt(currentId) -1 +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId < 378 && !isRightEdge) {
                const newId = squares[parseInt(currentId) +1 +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if (currentId < 379) {
                const newId = squares[parseInt(currentId) +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId == 398){
                const newId = squares[parseInt(currentId) +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId == 379){
                const newId = squares[parseInt(currentId) +width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId == 378){
                const newId = squares[parseInt(currentId) +width +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId == 20){
                const newId = squares[parseInt(currentId) -width].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }
            if(currentId == 21){
                const newId = squares[parseInt(currentId) -width -1].id
                const newSquare = document.getElementById(newId)
                click(newSquare, newId)
            }

        }, 1)
    }


    //add flags with right click
    function addFlag(square) {
        if (isGameOver) return
        if (!square.classList.contains('checked')) {
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
                        click(squares[i +1 -width], i +1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i - width],i - width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1],i  +1)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width],i  +1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +width],i  +width)
                    }
                }else if(isRightEdge){
                    if(isGameOver == false){
                        click(squares[i -1],i -1)
                    }
                    if(isGameOver == false){
                        click(squares[i - width],i - width)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width],i  -1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width],i  -1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +width],i  +width)
                    }
                }else if(i<20){
                    if(isGameOver == false){
                        click(squares[i -1],i -1)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1],i  +1)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width],i  -1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width],i  +1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +width],i  +width)
                    }
                }else if(i<400 && i>379){
                    if(isGameOver == false){
                        click(squares[i -1],i -1)
                    }
                    if(isGameOver == false){
                        click(squares[i +1 -width],i +1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i - width],i - width)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width],i  -1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1],i  +1)
                    }   
                }else if(i == 0){
                    if(isGameOver == false){
                        click(squares[i  +1],i  +1)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width],i  +1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +width],i  +width)
                    }   
                }else{
                    if(isGameOver == false){
                        click(squares[i -1],i -1)
                    }
                    if(isGameOver == false){
                        click(squares[i +1 -width],i +1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i - width],i - width)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 -width],i  -1 -width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1],i  +1)
                    }
                    if(isGameOver == false){
                        click(squares[i  -1 +width],i  -1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +1 +width],i  +1 +width)
                    }
                    if(isGameOver == false){
                        click(squares[i  +width],i  +width)
                    }                    
                }
                
            }
        }
    }


    let iLight;
    let lightOn = false
    //light on adjacent square when leftRightClick
    function lightLeftRightClick(i, square){
        const isLeftEdge = (i % width === 0)
        const isRightEdge = (i % width === width -1)
        if(lightOn == false){
            iLight = i
            lightOn = true
            if (iLight > 0 && !isLeftEdge && (squares[iLight -1].classList.contains('green') || squares[iLight -1].classList.contains('lightGreen'))) squares[iLight -1].style.filter = 'contrast(110%)'
            if (iLight > 19 && !isRightEdge && (squares[iLight +1 -width].classList.contains('green') || squares[iLight +1 -width].classList.contains('lightGreen'))) squares[iLight +1 -width].style.filter = 'contrast(110%)'
            if (iLight > 20 && (squares[iLight - width].classList.contains('green') || squares[iLight - width].classList.contains('lightGreen'))) squares[iLight - width].style.filter = 'contrast(110%)'
            if (iLight > 21 && !isLeftEdge && (squares[iLight  -1 -width].classList.contains('green') || squares[iLight  -1 -width].classList.contains('lightGreen'))) squares[iLight  -1 -width].style.filter = 'contrast(110%)'
            if (iLight < 398 && !isRightEdge && (squares[iLight  +1].classList.contains('green') || squares[iLight  +1].classList.contains('lightGreen'))) squares[iLight  +1].style.filter = 'contrast(110%)'
            if (iLight < 380 && !isLeftEdge && (squares[iLight  -1 +width].classList.contains('green') || squares[iLight  -1 +width].classList.contains('lightGreen'))) squares[iLight  -1 +width].style.filter = 'contrast(110%)'
            if (iLight < 378 && !isRightEdge && (squares[iLight  +1 +width].classList.contains('green') || squares[iLight  +1 +width].classList.contains('lightGreen'))) squares[iLight  +1 +width].style.filter = 'contrast(110%)'
            if (iLight < 379 && (squares[iLight  +width].classList.contains('green') || squares[iLight  +width].classList.contains('lightGreen'))) squares[iLight +width].style.filter = 'contrast(110%)'
            if (iLight === 398 && (squares[iLight  +1].classList.contains('green') || squares[iLight  +1].classList.contains('lightGreen'))) squares[iLight  +1].style.filter = 'contrast(110%)'
            if (iLight === 379 && (squares[iLight  +20].classList.contains('green') || squares[iLight  +20].classList.contains('lightGreen'))) squares[iLight  +20].style.filter = 'contrast(110%)'
            if (iLight === 378 && (squares[iLight  +21].classList.contains('green') || squares[iLight  +21].classList.contains('lightGreen'))) squares[iLight  +21].style.filter = 'contrast(110%)'
        }
    }

    function lightLeftRightClickOFF(){
        const isLeftEdge = (iLight % width === 0)
        const isRightEdge = (iLight % width === width -1)
        if(lightOn == true){
            lightOn = false
            if (iLight > 0 && !isLeftEdge && (squares[iLight -1].classList.contains('green') || squares[iLight -1].classList.contains('lightGreen'))) squares[iLight -1].style.filter = 'contrast(80%)'
            if (iLight > 19 && !isRightEdge && (squares[iLight +1 -width].classList.contains('green') || squares[iLight +1 -width].classList.contains('lightGreen'))) squares[iLight +1 -width].style.filter = 'contrast(80%)'
            if (iLight > 20 && (squares[iLight - width].classList.contains('green') || squares[iLight - width].classList.contains('lightGreen'))) squares[iLight - width].style.filter = 'contrast(80%)'
            if (iLight > 21 && !isLeftEdge && (squares[iLight  -1 -width].classList.contains('green') || squares[iLight  -1 -width].classList.contains('lightGreen'))) squares[iLight  -1 -width].style.filter = 'contrast(80%)'
            if (iLight < 398 && !isRightEdge && (squares[iLight  +1].classList.contains('green') || squares[iLight  +1].classList.contains('lightGreen'))) squares[iLight  +1].style.filter = 'contrast(80%)'
            if (iLight < 380 && !isLeftEdge && (squares[iLight  -1 +width].classList.contains('green') || squares[iLight  -1 +width].classList.contains('lightGreen'))) squares[iLight  -1 +width].style.filter = 'contrast(80%)'
            if (iLight < 378 && !isRightEdge && (squares[iLight  +1 +width].classList.contains('green') || squares[iLight  +1 +width].classList.contains('lightGreen'))) squares[iLight  +1 +width].style.filter = 'contrast(80%)'
            if (iLight < 379 && (squares[iLight  +width].classList.contains('green') || squares[iLight  +width].classList.contains('lightGreen'))) squares[iLight +width].style.filter = 'contrast(80%)'
            if (iLight === 398 && (squares[iLight  +1].classList.contains('green') || squares[iLight  +1].classList.contains('lightGreen'))) squares[iLight  +1].style.filter = 'contrast(80%)'
            if (iLight === 379 && (squares[iLight  +20].classList.contains('green') || squares[iLight  +20].classList.contains('lightGreen'))) squares[iLight  +20].style.filter = 'contrast(80%)'
            if (iLight === 378 && (squares[iLight  +21].classList.contains('green') || squares[iLight  +21].classList.contains('lightGreen'))) squares[iLight  +21].style.filter = 'contrast(80%)'

        }
    }
    
    //restart button
    const restartButton = document.getElementById('restartButton')
    restartButton.addEventListener("click", ()=>{restart()})

    function restart(){
        console.log('RESTART')
        grid.innerHTML = ''
        squares.length = 0
        isGameOver = false
        flags = 0
        build()
        $.ajax({
            type: "POST", 
            url: "./MinesweeperEasy/requests.php",
            data: {
                request: 'restart',
            }
        })
    }
})

