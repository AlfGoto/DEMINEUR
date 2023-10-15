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
    let isOnLogin = false
    let loginDiv = document.getElementById('loginDiv')
    let registerDiv = document.getElementById('registerDiv')
    let loginContent = document.getElementById('loginContent')
    let registerContent = document.getElementById('registerContent')
    registerDiv.classList.remove('registerDivClosed')
    loginDiv.classList.remove('loginDivOpen')
    loginDiv.classList.add('loginDivClosed')
    registerDiv.classList.add('registerDivOpen')
    loginContent.innerHTML = ' '
    loginRegisterInterface.addEventListener('mouseup', () => {
        if (isOnLogin == false){
            console.log('ouverture login')
            loginDiv.addEventListener('mousedown', () => {
                isOnLogin = true
                registerContent.innerHTML = ' '
                loginContent.innerHTML = "<form id='loginForm' method='post'><div id='loginPseudoDiv'><label class='notSelectable' id='loginPseudoLabel'>Pseudo : </label><input id='loginPseudoInput' type='texte' name='loginPseudo' required maxlength='10' class='textInput'></input></div><div id='loginPasswordDiv'><label class='notSelectable' id='loginPasswordLabel'>Password : </label><input id='loginPasswordInput' type='password' name='loginPassword' required minlength='5' maxlength='10' class='textInput'></input></div><div id='loginCookieDiv'><label class='notSelectable' id='loginCookieLabel'>Remember this computer for a year?</label><input id='loginCookieInput' type='checkbox' name='loginCookie'></input></div><input type='submit' value='Login' class='submitButton'></form>"
                loginDiv.classList.remove('loginDivClosed')
                registerDiv.classList.remove('registerDivOpen')
                registerDiv.classList.add('registerDivClosed')
                loginDiv.classList.add('loginDivOpen')
            })
        }
        if (isOnLogin == true){
            console.log('ouverture register')
            registerDiv.addEventListener('mousedown', () => {
                isOnLogin = false
                loginContent.innerHTML = ' '
                registerContent.innerHTML = "<form id='registerForm' method='post'><div id='registerPseudoDiv'><label class='notSelectable' id='registerPseudoLabel'>Pseudo : </label><input id='registerPseudoInput' type='texte' name='registerPseudo' required minlength='5' maxlength='10' class='textInput'></input></div><div id='registerPasswordDiv'><label class='notSelectable' id='registerPasswordLabel'>Password : </label><input id='registerPasswordInput' type='password' name='registerPassword' required minlength='5' maxlength='10' class='textInput'></input><p class='notSelectable'>don't put your usual password, <br/> i'm still working on making this site ultra safe</p></div><div id='registerCookieDiv'><label class='notSelectable' id='registerCookieLabel'>Remember this computer for a year ?</label><input id='registerCookieInput' type='checkbox' name='registerCookie'></input></div><input type='submit' value='Register' class='submitButton'></form>"
                registerDiv.classList.remove('registerDivClosed')
                loginDiv.classList.remove('loginDivOpen')
                loginDiv.classList.add('loginDivClosed')
                registerDiv.classList.add('registerDivOpen')
            })
        }
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
