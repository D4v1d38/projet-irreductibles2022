/* MAIN ELEMENTS */

/*-------------*/
/*====DATAS====*/
/*-------------*/

/* Get elements menu */
const menuBtn = document.getElementById('mobile-menu-btn');
const mainMenu = document.getElementById('menuPrincipal');

/* Get elements anchor */
const topAnchor = document.getElementById('top-page-anchor');

/*-----------------*/
/*====FUNCTIONS====*/
/*-----------------*/

//Show Or Hide selected items
function displayMenu(item){
    item.classList.toggle('display-item');
}
/*-----------------*/
/*=======MAIN======*/
/*-----------------*/
document.addEventListener('DOMContentLoaded',function(){

    //click event for display main menu for mobile & tablet devices
    menuBtn.addEventListener('click',()=>{
        displayMenu(mainMenu);
    });

    //scroll event for display anchor for top-page
    window.addEventListener('scroll',()=>{
        if(scrollY/innerHeight >= 0.33){
                topAnchor.classList.add('visible');
                topAnchor.classList.remove('invisible');     
        }else{
            topAnchor.classList.add('invisible');
            topAnchor.classList.remove('visible');
        }
    })
    
});