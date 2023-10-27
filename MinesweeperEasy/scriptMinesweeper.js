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
    }



    //left click
    function click(square, i){
        if(square.classList.contains('checked')){return}
        console.log(square.getAttribute('id'))
        $.ajax({
            type: "POST", 
            url: "./MinesweeperEasy/requests.php",
            data: {
                request: 'click',
                idSquare: square.getAttribute('id')
            },
            success: function(response) {
                console.log(response)
                var result = JSON.parse(response);
                if (result.isBomb) {
                    console.log("C'est une bombe! Vous avez perdu!");
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
                    
                    //differents colors for the numbers
                    switch(result.data){
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

    
    
})

