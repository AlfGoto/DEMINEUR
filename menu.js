document.addEventListener('DOMContentLoaded', () => {
    let menu = document.getElementById('menuDiv')
    let menuPetitDiv = document.getElementById('menuPetitDiv')
    let restartButton = document.getElementById('restartButton')
    let open = 0
    let restart = 'no'
    let fullscreenButton = document.getElementById('fullscreenButton')
    let fullscreen = false
    let myDocument = document.documentElement
    let muteButton = document.getElementById('muteButton')
    let buttons = document.querySelectorAll('.button')



    //Menu oppening, closing and animation
    restartButton.addEventListener('pointerdown', () => {restart = 'yes'})
    restartButton.addEventListener('pointerup', () => setTimeout(() => {restart = 'no'},'100'))
    fullscreenButton.addEventListener('pointerdown', () => {restart = 'yes'})
    fullscreenButton.addEventListener('pointerup', () => setTimeout(() => {restart = 'no'},'100'))
    muteButton.addEventListener('pointerdown', () => {restart = 'yes'})
    muteButton.addEventListener('pointerup', () => setTimeout(() => {restart = 'no'},'100'))

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
    buttons.forEach((buttons) => {
        buttons.addEventListener('pointerover', () => {
            menuPetitDiv.classList.remove('hover')
        })
        buttons.addEventListener('pointerout', () => {
            menuPetitDiv.classList.add('hover')
        })
    })
    


    




    //fullscreen button
    fullscreenButton.addEventListener('pointerdown', () => {
        if(fullscreen == false){
            if(myDocument.requestFullscreen){
                myDocument.requestFullscreen()
            }
            else if(myDocument.msRequestFullscreen){
                myDocument.msRequestFullscreen()
            }
            else if(myDocument.mozRequestFullscreen){
                myDocument.mozRequestFullscreen()
            }
            else if(myDocument.webkitRequestFullscreen){
                myDocument.webkitRequestFullscreen()
            }
            console.log('fullscreen false')
            fullscreenButton.innerHTML = "<img src ='./image/unFullscreenLogo.png' alt='button to set fullscreen on and off' id='fullscreenImg'>"
            fullscreen = true
        }else if(fullscreen == true){
            if(document.exitFullscreen){
                document.exitFullscreen()
            }
            else if(document.msexitFullscreen){
                document.msexitFullscreen()
            }
            else if(document.mozexitFullscreen){
                document.mozexitFullscreen()
            }
            else if(document.webkitexitFullscreen){
                document.webkitexitFullscreen()
            }
            console.log('fullscreen true')
            fullscreenButton.innerHTML = "<img src ='./image/fullscreenLogo.png' alt='button to set fullscreen on and off' id='fullscreenImg'>"
            fullscreen = false
        }
    })


    //mute button
    muteButton.addEventListener('pointerdown', () => {
        mute = !mute
        if(mute == true){
            muteButton.innerHTML = "<img src ='./image/mute.png' alt='button to set a mute on and off' id='muteImg'></img>"
            $.ajax({
                type: "POST", 
                url: "JStoCookies.php",
                data: { 
                    mute: true
                },
            })
        } else if(mute == false){
            muteButton.innerHTML = "<img src ='./image/unMute.png' alt='button to set a mute on and off' id='muteImg'></img>"
            $.ajax({
                type: "POST", 
                url: "JStoCookies.php",
                data: { 
                    mute: false
                },
            })
        }
    })
    //mute Cookie
    if(typeof muteCookie !== 'undefined'){
        if(muteCookie == true){
            muteButton.innerHTML = "<img src ='./image/mute.png' alt='button to set a mute on and off' id='muteImg'></img>"
        } else if(muteCookie == false){
            muteButton.innerHTML = "<img src ='./image/unMute.png' alt='button to set a mute on and off' id='muteImg'></img>"
        }
    }
    



    //Table animation
    let statsDiv = document.getElementById('statsDiv')
    let statsDivOpen = true
    statsDiv.style.transition = 'height 0.5s ease';
    statsDiv.addEventListener('click', () => {
        if(statsDivOpen){
            statsDivOpen = false
            statsDiv.style.height = 'auto'
        }else{
            statsDivOpen = true
            statsDiv.style.height = '7vh'
        }
    })

    let tableDiv = document.getElementById('tableDiv')
    let tableOpen = true
    // tableDiv.style.transition = 'height 0.5s ease';
    tableDiv.addEventListener('click', () => {
        if(tableOpen){
            tableOpen = false
            tableDiv.style.height = 'auto'
        }else{
            tableOpen = true
            tableDiv.style.height = '7vh'
        }
    })

    let tablePlayerDiv = document.getElementById('tablePlayersDiv')
    let tablePlayerOpen = true
    // tablePlayerDiv.style.transition = 'height 0.5s ease';
    tablePlayerDiv.addEventListener('click', () => {
        if(tablePlayerOpen){
            tablePlayerOpen = false
            tablePlayerDiv.style.height = 'auto'
        }else{
            tablePlayerOpen = true
            tablePlayerDiv.style.height = '7vh'
        }
    })


    

})

