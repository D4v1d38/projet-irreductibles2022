//Error manager

class FormError{

        constructor(field,errorMessage){
            this.field          = field;
            this.errorMessage   = errorMessage;
            this.showError();
        }

        showError(){

            //get form fields 
            const target = document.forms['form'].elements[this.field];

            //if element doesn"t exist : create span
            if(!target.nextElementSibling){
                
                const errorSpan = document.createElement('span');
                errorSpan.classList.add('form-error-message');
                errorSpan.textContent = this.errorMessage;
                document.forms['form'].elements[this.field].after(errorSpan);
            }
        }
}


/*-----------------*/
/*====EXPORT====*/
/*-----------------*/
export default FormError;