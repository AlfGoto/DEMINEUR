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


        //clicks
        //normal click
        square.addEventListener('mousedown', function(e) {
            if (e.button === 0 ){
                click(square)
            }
        
        })
    }



    function click(square){
        let isSquareBomb;
                
                console.log(square.getAttribute('id'))
                $.ajax({
                    type: "POST", 
                    url: "./Minesweeper easy mode/scriptMinesweeper.php",
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

