/*-----------------*/
/*====IMPORT====*/
/*-----------------*/
import ChekerData from './modules/CheckerData.js';


/*----------------------*/
/*====DECLARATIONS==-==*/
/*--------------------*/
let formInputs;
let formTextArea;
let formSelect; 
let fieldsToCheck=[];

/*----------------------*/
/*===== FUNCTIONS =====*/
/*--------------------*/

// New instance for check datas values
function dataCheck(e){

    let chekDatas = new ChekerData();
    chekDatas.getDatas(fieldsToCheck);

    let validation = chekDatas.formValidation();
    
    if(validation == false){
        e.preventDefault();
    }
}


// function for hide error after fields 
function errorDisplay(){
    try{
        if(this.nextElementSibling.classList.contains('form-error-message')){
            this.nextElementSibling.remove();
        }
    }
    catch(error){
        console.warn(error + "éléments en dehors du contexte de la page")
    }
}

/*----------------------*/
/*======== MAIN ========*/
/*---------------------*/
document.addEventListener('DOMContentLoaded', function(){
    
    //form selsction
    let form = document.querySelector("form");
    
    //sublit event
    form.addEventListener('submit',dataCheck);
    
    //get fields elements
    formInputs      = document.querySelectorAll("input");
    formTextArea    = document.querySelectorAll("textarea");
    formSelect      = document.querySelectorAll("select");
    
    // event for inputs
    for(const inputItem of formInputs){
        inputItem.addEventListener('input',errorDisplay);
        fieldsToCheck.push(inputItem);
    }
    
    // event for TextArea
    for(const textAreaItem of formTextArea){
        textAreaItem.addEventListener('input',errorDisplay);
        fieldsToCheck.push(textAreaItem);
    }
    
    // event for select
    for(const SelectItem of formSelect){
        SelectItem.addEventListener('input',errorDisplay);
        fieldsToCheck.push(SelectItem);
    }
})