document.addEventListener('DOMContentLoaded', () => {

    //nav login and register interface
    let navLoginSelected = document.getElementById('navLoginSelected')
    let loginClick = document.getElementById('loginClick')
    let registerClick = document.getElementById('registerClick')
    let loginDiv = document.getElementById('loginDiv')
    let registerDiv = document.getElementById('registerDiv')
    let loginSelected = true
    loginClick.addEventListener('click', () => {
        if (loginSelected == false){
            loginSelected = true
            navLoginSelected.style.transition = 'left 0.3s ease';
            navLoginSelected.style.left = '0vw'
            loginDiv.style.left = '1.5vw'
            registerDiv.style.left = '26vw'
        }
    })
    registerClick.addEventListener('click', () => {
        if(loginSelected == true){
            loginSelected = false
            navLoginSelected.style.transition = 'left 0.3s ease';
            navLoginSelected.style.left = '7.8vw'
            loginDiv.style.left = '-24vw'
            registerDiv.style.left = '1.5vw'
        }
    })
    
    
    
    
    
})