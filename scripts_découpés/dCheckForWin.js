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