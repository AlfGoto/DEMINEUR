document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('.grid');
    let width = 20
    let bombAmount = 20
    let squares = []
    let isGameOver = false
    let flags = 0
    let firstSquare = 1

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

        let firstSquare = 1


        //créé les squares
        for(let i = 0; i < width*width; i++) {
            const square = document.createElement('div');
            square.setAttribute('id', i)
            square.classList.add(shuffledArray[i])
            grid.appendChild(square);
            squares.push(square);

            //normal click
            square.addEventListener('mousedown', function(e) {
                if (e.button === 0 ){
                    click(square)
                }
                
            })

            //cntrl and left click
            square.oncontextmenu = function(e) {
                e.preventDefault()
                addFlag(square)
            }
        }

        //numbers on square
        for (let i = 0; i < squares.length; i++) {
            let total = 0
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
                squares[i].setAttribute('data', total)
                console.log(squares[i])
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
                square.innerHTML = 'F'
                flags++
            } else {
                square.classList.remove('flag')
                square.innerHTML = ''
                flags --
            }
        }
    }
    

    //click on square action
    function click(square){
        let currentId = square.id
        if ((firstSquare === 1) && square.classList.contains('bomb')) {
        }
        if (isGameOver) return
        if (square.classList.contains('checked') || square.classList.contains('flag')) return
        if (square.classList.contains('bomb')) {
            alert('Game over')
            isGameOver = true
        }else {
            let total = square.getAttribute('data')
            if (total !=0) {
                square.classList.add('checked')
                square.innerHTML = total
                firstSquare = 0
                console.log(firstSquare)
                checkForWin()
                return
            }
            checkSquare(square, currentId) 
        }
        square.classList.add('checked')
        checkForWin()
    }

  

    //check les carrés voisins quand le carré est cliqué
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
            if(currentId < 398 && !isRightEdge) {
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
            if (currentId === 398){
                const newId = squares[parseInt(currentId) +1].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            } 
            if (currentId === 379) {
                const newId = squares[parseInt(currentId) +20].id
                const newSquare = document.getElementById(newId)
                click(newSquare)
            } 
            if (currentId === 378) {
                const newId = squares[parseInt(currentId) +21].id
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
            if (matches === (width*width-bombAmount)) {
                alert('YOU WIN')
                isGameOver = true
            }
        }
        
    }



    //reloadscript
    function reloadScript() {
        with(document) {
         var newscr = createElement('script');
         newscr.id = 'demineurScript';
         newscr.appendChild(createTextNode(getElementById('demineurScript').innerHTML));
         body.removeChild(getElementById('demineurScript'));
         body.appendChild(newscr);
        }
       }


    //restart
    const buttonRestart = document.getElementById('buttonRestart')
    buttonRestart.addEventListener("click", function(){
        grid.innerHTML = ''
        flags = 0
        squares.length = 0
        isGameOver = false
        createBoard()
    })
    

    



    
})