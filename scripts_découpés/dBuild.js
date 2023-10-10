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
            squares[i].setAttribute('data', total)
            console.log(squares[i])
        }
    }





}