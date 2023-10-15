document.addEventListener('DOMContentLoaded', () => {
    //Only show login and register Interface if not Logged
    let loginRegisterInterface = document.getElementById('loginRegisterInterface')
    let loggedInterface = document.getElementById('loggedInterface')


    if(isLogged){
            loginRegisterInterface.classList.remove('visible')
            loginRegisterInterface.classList.add('hidden')
            loggedInterface.classList.remove('hidden')
            loggedInterface.classList.add('visible')
        }else{
            loginRegisterInterface.classList.remove('hidden')
            loginRegisterInterface.classList.add('visible')
            loggedInterface.classList.remove('visible')
            loggedInterface.classList.add('hidden')
        }

    //Swap between Login And Register
    let loginGoToRegister = document.getElementById('goToRegister')
    let registerGoToLogin = document.getElementById('goToLogin')
    let loginInterface = document.getElementById('loginDiv')
    let registerInterface = document.getElementById('registerDiv')
    loginInterface.classList.add('hidden')
    loginGoToRegister.addEventListener('mousedown', function(e) {
        registerInterface.classList.remove('hidden')
        registerInterface.classList.add('visible')
        loginInterface.classList.remove('visible')
        loginInterface.classList.add('hidden')
        
    })
    registerGoToLogin.addEventListener('mousedown', function(e) {
        loginInterface.classList.remove('hidden')
        loginInterface.classList.add('visible')
        registerInterface.classList.remove('visible')
        registerInterface.classList.add('hidden')
    })

})
