document.addEventListener('DOMContentLoaded', () => {



    //Vars
    const grid = document.querySelector('.grid');
    let width = 20
    let squares = []
    


    //PHP Vars





    //BUILD
    //create squares
    for(let i = 0; i < width*width; i++) {
        const square = document.createElement('div');
        square.setAttribute('id', i)
        grid.appendChild(square);
        squares.push(square)


        //squares grid
        if(i<20 || (i>39 && i<60) || (i>39 && i<60) || (i>79 && i<100) || (i>119 && i<140) || (i>159 && i<180) || (i>199 && i<220) || (i>239 && i<260) || (i>279 && i<300)|| (i>319 && i<340) || (i>359 && i<380)){
            if(i%2 == 0){
                square.classList.add('green')
            }else{
                square.classList.add('lightGreen')
            }
        }
        if((i>19 && i<40) || (i>59 && i<80) || (i>19 && i<40) || (i>99 && i<120)|| (i>139 && i<160) || (i>179 && i<200) || (i>219 && i<240)|| (i>259 && i<280) || (i>299 && i<320) || (i>339 && i<360) || (i>379 && i<400)){
            if(i%2 == 0){
                square.classList.add('lightGreen')
            }else{
                square.classList.add('green')
            }
        }



        

        //clicks
        //normal click
        square.addEventListener('mousedown', function(e) {
            if (e.button === 0 ){
                click(square)
            }
        
        })
    }



    function click(square){
        console.log(square.getAttribute('id'))
        $.ajax({
            type: "POST", 
            url: "./MinesweeperEasy/requests.php",
            data: {
                request: 'click',
                idSquare: square.getAttribute('id')
            },
            success: function(response) {
                console.log(response)
                var result = JSON.parse(response);
                if (result.isBomb) {
                    console.log("C'est une bombe! Vous avez perdu!");
                } else {
                    console.log("Ce n'est pas une bombe. Continuez à jouer.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX: " + error);
            }
        })
    }
    
})

