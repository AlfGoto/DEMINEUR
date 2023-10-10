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