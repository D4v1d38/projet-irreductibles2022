/*-----------------*/
/*====FUNCTIONS====*/
/*-----------------*/
function game(){
    
    $.post("index.php?action=game_Ajax",showGame);
    
}

//AJAX response for gameday informations--->  callBack

function showGame(data){
    $("#gameDay").html(data);
    setInterval(game(),5000);
}

// Event 
$(function(){

    game();

});