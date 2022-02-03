/* PROGRESS BAR FOR MOBILE and TABLET */

/*-------------*/
/*====DATAS====*/
/*-------------*/

/* Get element*/
const progressBar = document.getElementById('progress-bar');

/*==== FUNCTIONS ====*/

// get document height and client height for calculate percent position 
function calculProgress(){

    window.addEventListener('scroll',function(){
        const percentWIndow = scrollY/ (document.body.offsetHeight-innerHeight)*100;
        progressBar.style.width = percentWIndow+"%";
    })
}

/*==== MAIN ====*/
document.addEventListener('DOMContentLoaded',function(){
    calculProgress();
});