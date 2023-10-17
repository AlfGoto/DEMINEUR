document.addEventListener('DOMContentLoaded', () => {
    let menu = document.getElementById('menuDiv')
    let menuPetitDiv = document.getElementById('menuPetitDiv')
    let restartButton = document.getElementById('restartButton')
    let open = 1
    let restart = 'no'


    restartButton.addEventListener('pointerdown', () => {restart = 'yes'})
    restartButton.addEventListener('pointerup', () => delay(10).then(() => {restart = 'no'}))

    menuPetitDiv.addEventListener('pointerdown', () => {
        console.log('menuPetitDiv click')
        if(open == 1 && restart == 'no'){
            console.log('open = 1')
            menu.style['right'] =  '-20vw'
            open = 0
        }else if(open == 0 && restart == 'no'){
            console.log('open = 0')
            menu.style['right'] =  '0vw'
            open = 1
        }
    })
})
