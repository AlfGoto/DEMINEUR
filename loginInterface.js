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
    let whatIsOn = register
    let loginContent = document.getElementById('loginContent')
    let registerContent = document.getElementById('registerContent')
    let loginDiv = document.getElementById('loginDiv')
    let registerDiv = document.getElementById('registerDiv')
    loginContent.classList.add('hidden')
    if(whatIsOn == 'register'){
        loginDiv.addEventListener('mousedown', function(e) {
        registerContent.classList.remove('hidden')
        registerContent.classList.add('visible')
        loginContent.classList.remove('visible')
        loginContent.classList.add('hidden')
            
        })
    }



    
    registerGoToLogin.addEventListener('mousedown', function(e) {
        loginInterface.classList.remove('hidden')
        loginInterface.classList.add('visible')
        registerInterface.classList.remove('visible')
        registerInterface.classList.add('hidden')
    })










    /*

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

    */

})
