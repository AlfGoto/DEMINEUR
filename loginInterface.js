document.addEventListener('DOMContentLoaded', () => {
    //Only show login and register Interface if not Logged
    let loginRegisterInterface = document.getElementById('loginRegisterInterface')
    let loggedInterface = document.getElementById('loggedInterface')
    let unlogButton = document.getElementById('unlogButtonDiv')


    if(isLogged == true){
            loginRegisterInterface.classList.remove('visible')
            loginRegisterInterface.classList.add('hidden')
            loggedInterface.classList.remove('hidden')
            loggedInterface.classList.add('visible')
            unlogButton.classList.remove('hidden')
            unlogButton.classList.add('visible')
        }else{
            loginRegisterInterface.classList.remove('hidden')
            loginRegisterInterface.classList.add('visible')
            loggedInterface.classList.remove('visible')
            loggedInterface.classList.add('hidden')
            unlogButton.classList.remove('visible')
            unlogButton.classList.add('hidden')
        }




})
