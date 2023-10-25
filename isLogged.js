document.addEventListener('DOMContentLoaded', () => {
    //Only show login and register Interface if not Logged

    let loggedInterface = document.getElementById('loggedInterface')
    let unlogButton = document.getElementById('unlogButtonDiv')
    let statsDiv = document.getElementById('statsDiv')


        if(isLogged == true){
            loggedInterface.classList.remove('hidden')
            loggedInterface.classList.add('visible')
            unlogButton.classList.remove('hidden')
            unlogButton.classList.add('visible')
            statsDiv.classList.remove('hidden')
            statsDiv.classList.add('visible')
        }else{

            loggedInterface.classList.remove('visible')
            loggedInterface.classList.add('hidden')
            unlogButton.classList.remove('visible')
            unlogButton.classList.add('hidden')
            statsDiv.classList.remove('visible')
            statsDiv.classList.add('hidden')
        }
    
    

    




})
