/*-----------------*/
/*====IMPORT====*/
/*-----------------*/
import FormError from "./FormError.js";

//check datas values
class CheckerData{
    
    constructor(){
        this.errorForm          = false;
        this._firstname         = "";
        this._name              = "";
        this._phone             = "";
        this._email             = "";
        this._object            = "";
        this._messageContent    = "";
        this._telephone         = "";
        this._number            = "";
        this._nation            = "";
        this._height            = "";
        this._weight            = "";
        this._birthdate         = "";
        this._articleTitle      = ""; 
        this._articleContent    = "";
        this._password          = "";

        //REGEX 
        this.nameReg    = /^[a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+([-'\s][a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+)?$/;
        this.regMail    = /^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/i;
        this.regPhone   = /^(0)[1-9][0-9]{8}$/;
        this.regNation  = /[a-zA-Z-çéèêàiï]/;
    }
    
    // get fields values 
    getDatas(datas){

        for(const data of datas){

            switch(data.name){
                case "firstname":
                    this.firstname = data.value;
                break;
                case "name":
                    this.name = data.value;
                break;
                case "email":
                    this.email = data.value;
                break;
                case "object":
                    this.object = data.value;
                break;
                case "content":
                    this.messageContent = data.value;
                break;
                case "tel":
                    this.telephone = data.value;
                break
                case "number":
                    this.number = data.value;
                break
                case "nation":
                    this.nation = data.value;
                break
                case "height" :
                    this.height = data.value;
                break
                case "weight" :
                    this.weight = data.value;
                break
                case "birthdate" :
                    this.birthdate = data.value;
                break;
                case "article_titre" :
                    this.articleTitle = data.value;
                break;
                case "article_contenu" :
                    this.articleContent = data.value;
                break;
                case "password" :
                    this.password = data.value;
                break;
            }
        }
    }

    /*===IDENTITIES CHECK===*/

    // firstname check
    get firstname(){
        return this._firstname;
    }

    set firstname(validFirstName){
        if(validFirstName == ""){
            new FormError("firstname" ,"Veuillez saisir ce champ");
            this.errorForm = true;
        }else if(! validFirstName.match(this.nameReg)){
            new FormError("firstname" ,"Le format du prénom n'est pas valide");
            this.errorForm = true;
        }else{
            this._firstname = validFirstName;
        }
    }
    
    // Name Check
    get name(){
        return this._name;
    }

    set name(validName){
        if(validName == ""){
            new FormError("name" ,"Veuillez saisir ce champ");
            this.errorForm = true;
        }else if(! validName.match(this.nameReg)){
            new FormError("name" ,"Le format du nom n'est pas valide");
            this.errorForm = true;
        }else{
            this._name = validName;
        }
    }
    
    /*===CONTACT CHECK===*/
    
    // email check
    get email(){
        return this._email;
    }
    
    set email(validEmail){
        
        if(validEmail==""){
            
            new FormError("email" ,"Veuillez saisir ce champ");
            this.errorForm = true;
            
        }else if(! validEmail.match(this.regMail)){
            
            new FormError("email" ,'Format du mail incorrect');
            this.errorForm = true;
            
        }else{
            this._email = validEmail;
        }
    }
    
    get telephone(){
        return this._telephone;
    }
    
    set telephone(validPhone){
        
        if(validPhone.match(this.regPhone) || validPhone == ""){
            
            this._telephone = validPhone;
            
        }else{
            
            new FormError("tel" ,"Le format du numéro n'est pas valide");
            this.errorForm = true;
        }
    }
    
    
    /*===MESSAGES CHECK===*/
    
    //object check
    get object(){
        return this._email;
    }
    
    set object(validObject){
        if(validObject==""){
            
            new FormError("object", "Veuillez saisir ce champ");
            this.errorForm = true;
            
        }else{
            this._object = validObject;
        }
    }
    
    //message content check
    get messageContent(){
        return this._messageContent;
    }
    
    
    set messageContent(validContent){
        if(validContent == ""){
            
            new FormError("content", "Veuillez saisir ce champ");
            this.errorForm = true;
            
        }else{
            this._messageContent = validContent;
        }
    }
    
    /*===PLAYERS INFOS ===*/
    
    get number(){
        return this._number;
    }
    
    set number(validNumber){
        
        if(validNumber == ""){
            
            new FormError("number", "Veuillez saisir ce champ");
            this.errorForm = true;
            
        }else if(validNumber < 1 || validNumber > 99 || isNaN(validNumber) ){
            
            new FormError("number", "Le numéro du joueur doit être compris entre 1 et 99");
            this.errorForm = true;
            
        }else{
            this._number= validNumber;
        }
    }
    
    get nation(){
        return this._nation;
    }
    
    set nation(validNation){
        
        if(validNation == ""){
            
            new FormError("nation", "Veuillez saisir ce champ");
            this.errorForm = true;
            
        }else if(!validNation.match(this.regNation)){
            
            new FormError("nation", "Le format de la nationalité n'est pas correct");
            this.errorForm = true;
            
        }else{
            this._nation= validNation;
        }
    }
    
    get height(){
        return this._height;
    }
    
    set height(validHeight){
        if(validHeight==""){
            new FormError("height", "Veuillez saisir ce champ");
            this.errorForm = true;           
        }else if(validHeight < 150 || validHeight > 220 || isNaN(validHeight)){
            new FormError("height", "La taille doit être compris entre 150cm et 220cm");
            this.errorForm = true;
        }else{
            this._height = validHeight;
        }
    }
    
    get weight(){
        return this._weight;
    }
    
    set weight(validWeight){
        if(validWeight==""){
            new FormError("weight", "Veuillez saisir ce champ");
            this.errorForm = true;           
        }else if(validWeight < 60 || validWeight > 120 || isNaN(validWeight)){
            new FormError("weight", "Le poids doit être compris entre 60 et 120kg");
            this.errorForm = true;
        }else{
            this._weight = validWeight;
        }
    }
    
    get birthdate(){
        return this._birthdate;
    }
    
    set birthdate(validBirthdate){
        
        //  CHECK Birthdate and age
        // check with actual year and birth Year
        
        let actualDate = new Date();
        let actualYear= actualDate.getFullYear();
        let birthYear =validBirthdate.substring(0,4);
        let age = (actualYear - birthYear );

        if(validBirthdate == ""){
            new FormError("birthdate", "Veuillez remplir ce champ");
            this.errorForm = true;
        }else if(age < 0){
            new FormError("birthdate", "le joueur n'est pas encore né ;)");
            this.errorForm = true;  
        }else if(age < 15 || age > 45){
            new FormError("birthdate", "l'age du joueur n'est pas cohérent");
            this.errorForm = true;           
        }else{
            this._birthdate =  validBirthdate;
        }
    }
    
    /*=== ARTICLES CHECK===*/
    
    get articleTitle(){
        return this._articleTitle;
    }
    
    set articleTitle(validArticleTitle){
        if(validArticleTitle ==""){
            new FormError("article_titre","Veuillez remplir ce champ");
            this.errorForm = true;
        }else{
            this._articleTitle =  validArticleTitle;
        }
    }
    
    get articleContent(){
        return this._articleContent;
    }
    
    set articleContent(validArticleContent){
        if(validArticleContent ==""){
            new FormError("article_contenu","Veuillez remplir ce champ");
            this.errorForm = true;
        }else{
            this._articleContent = validArticleContent;
        }
    }
    
    /*=== PASSWORD CHECK==*/
    
    get password(){
        return this._password;
    }
    
    set password(validPassword){
        if(validPassword ==""){
            new FormError("password","Veuillez remplir ce champ");
            this.errorForm = true; 
        }else{
            this._password = validPassword;
        }
    }

    /*===FORM VALIDATION===*/
    
    //check error 
    formValidation(){
        if(this.errorForm  == false){
            return true;
        }else{
            return false;
        }
    }
}

/*-----------------*/
/*====EXPORT====*/
/*-----------------*/
export default CheckerData;