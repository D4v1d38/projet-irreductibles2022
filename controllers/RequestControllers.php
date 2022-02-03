<?php

//inclusion du model
    require 'models/Request.php';
    

    class RequestControllers
    {


        //CONSTRUCTOR
        public function __construct(){
            $this->request = new Request();
            $this->msg     = new Functions();
            $this->connect  = new AdminControllers();
        }

        // METHODS

        public function addRequest():void{
            if(!empty($_POST)){
                if(isset($_POST['firstname'],$_POST['name'],$_POST['tel'],$_POST['email'],$_POST['object'],$_POST['content'])){
                    if(!empty($_POST['firstname']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['object']) && !empty($_POST['content'])){
                        
                        $error      = false;
                        $regPhone   = "/^(0)[1-9][0-9]{8}$/";
                        $regName    = "/^[a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+([-'\s][a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+)?$/";
                        
                        $firstname  = $_POST['firstname'];
                        $name       = $_POST['name'];
                        $email      = $_POST['email'];
                        $object     = $_POST['object'];
                        $content    = $_POST['content'];
                        
                        //Identity check
                        if(!preg_match($regName, $firstname)){
                            $error = true;
                        }
                        
                        if(!preg_match($regName, $name)){
                            $error = true;
                        }
                        
                        //Phone check
                        if(empty($_POST['tel'])){
                            $tel = "non renseigné";
                        }else{
                            if(preg_match($regPhone, $_POST['tel'] )){
                                $tel = $_POST['tel'];
                            }else{
                                $error = true;
                            }
                        }
                        
                        //Mail check
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $error = true;
                        }
                        
                        //Submit form
                        if($error === false ){
                            $addRequest =  $this->request->addRequest($firstname,$name,$email,$object,$content,$tel);
                        }else{
                            $this->msg->showMessage("Certains champs ne remplissent pas les conditions de validation, veuillez ressaisir votre demande","contact_us","error");
                        } 
                        
                        if($addRequest){
                            $this->msg->showMessage("Votre message à bien été envoyé","contact_us","success"); 
                        }
                        
                    }else{
                        $this->msg->showMessage("Veuillez remplir les champs obligatoires","contact_us","error");
                    }
                }else{
                    $this->msg->showMessage("Une erreur est survenu lors de l'envoi de votre demande","contact_us","error");
                }
            }else{
                $template = 'www/contactUs/contactUs';
                require 'www/layout.phtml';
            }
        }

        // Get list of request for manage
        public function requestManager():void{
            
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
            
            $requestList = $this->request->getRequestList();
            $template="www/admin/requestManagement";
            require "www/layout.phtml";
        }
        
        // Details of a request
        public function requestDetails():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
                
            if(array_key_exists('id_message',$_GET) && is_numeric($_GET['id_message'])){
                $idRequest = $_GET['id_message'];
                $detailsRequest = $this->request->getRequestById($idRequest);

                $template = "www/contactUs/detailRequest";
                require "www/layout.phtml";
            }else{
                header('location:index.php');
                exit();
            }
        }

        // Delete request by id
        public function requestDelete():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(isset($_POST['message_delete']) && is_numeric($_POST['message_delete'])){
                $idRequest = $_POST['message_delete'];
                $deletMsg = $this->request->deleteRequestById($idRequest);

                if($deletMsg ==true){
                    $this->msg->showMessage("Le message a bien été supprimé","message_manager","success");
                }else{
                    $this->msg->showMessage("Une erreur est survenue lors de la suppression du message","admin", "error");
                }
            }else{
                header('location:index.php');
                exit();
            }
        }
    }