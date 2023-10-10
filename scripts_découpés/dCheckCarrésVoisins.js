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
        if(currentId < 399 && !isRightEdge) {
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


    }, 10)
}