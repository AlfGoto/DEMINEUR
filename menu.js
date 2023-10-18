document.addEventListener('DOMContentLoaded', () => {
    let menu = document.getElementById('menuDiv')
    let menuPetitDiv = document.getElementById('menuPetitDiv')
    let restartButton = document.getElementById('restartButton')
    let open = 0
    let restart = 'no'


    restartButton.addEventListener('pointerdown', () => {restart = 'yes'})
    restartButton.addEventListener('pointerup', () => setTimeout(() => {restart = 'no'},'100'))

    menuPetitDiv.addEventListener('pointerdown', () => {
        if(open == 1 && restart == 'no'){
            menu.style.transition = 'right 0.3s ease';
            menu.style.right = '-20vw';
            open = 0;
            menuPetitDiv.classList.add('closed')
            menuPetitDiv.classList.remove('open')
        } else if(open == 0 && restart == 'no'){
            menu.style.transition = 'right 0.3s ease';
            menu.style.right = '0vw';
            open = 1;
            menuPetitDiv.classList.add('open')
            menuPetitDiv.classList.remove('closed')
        }
    });


})

