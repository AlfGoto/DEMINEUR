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