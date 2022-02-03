/*-------------*/
/*====DATAS====*/
/*-------------*/

/* get elements */
const slider    = document.getElementById('slider-wrapper');
const slides    = document.querySelectorAll('.slide');
const suivant   = document.querySelector('.right');
const precedent = document.querySelector('.left');

/* declarations & assignations */
const nbSlide = slides.length;
let count = 0;
let slideTimer;


/*------------------*/
/*====FUNCTIONS====*/
/*----------------*/


// change slide :next
function slideSuivante(){
    slides[count].classList.remove('active'); 

    if(count < nbSlide - 1){
        count++;
    } else {
        count = 0;
    }

    slides[count].classList.add('active');
}

// change slide :previous
function slidePrecedente(){
    slides[count].classList.remove('active');

    if(count > 0){
        count--;
    } else {
        count = nbSlide - 1;
    }
    
    slides[count].classList.add('active');
}

// Key event for change slide with arrows keys
function keyPress(e){
    
    if(e.code === 'ArrowLeft'){
        slidePrecedente();
    } else if(e.code === 'ArrowRight'){
        slideSuivante();
    }
}


/*----------------------*/
/*=========MAIN=========*/
/*----------------------*/
document.addEventListener('DOMContentLoaded',function(){

    /* EVENTS FOR SLIDER */

    try{
    // events for change slides   
    suivant.addEventListener('click', slideSuivante);
    precedent.addEventListener('click', slidePrecedente);
    document.addEventListener('keydown', keyPress);
    
    /*slider timer, change slide every XXXX ms*/
    slideTimer = setInterval(slideSuivante,4000);
    }
    catch(e){
        console.warn(e +" :message provenant du slider")
    }
})



