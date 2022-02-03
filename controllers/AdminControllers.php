<?php
    require "models/Admin.php";
    
    class AdminControllers
    {
        private object $administrator;
        
        //CONSTRUCTOR
        public function __construct(){
            $this->administrator = new Admin();
            $this->msg           = new Functions();
        }
        
        // form admin connect or home admin if admin is connect
        public function admin():void{
            $template="admin/homeAdmin";

            if(isset($_POST['email'], $_POST['password'])){
                if(!empty($_POST['email']) && !empty($_POST['password'])){
                    
                    $email      = trim($_POST['email']);
                    $password   = trim($_POST['password']);
                    
                    $admin = $this->administrator->getAdminByEmail($email);
                    
                    if(!$admin){
                        $this->msg->showMessage("L'adresse mail n'existe pas !","admin","error");
                    }
                    else{
                        if(password_verify($password, $admin['password'])){
                            
                            $_SESSION=[
                                'idAdmin'       =>  htmlspecialchars($admin['id_admin']),
                                'nomAdmin'      =>  htmlspecialchars($admin['nom']),
                                'prenomAdmin'   =>  htmlspecialchars($admin['prenom']),
                                'emailAdmin'    =>  htmlspecialchars($admin['email']),
                                'fonctionAdmin' =>  htmlspecialchars($admin['role']),
                                'niveauAdmin'   =>  htmlspecialchars($admin['niveau_admin'])
                                ];
                        }else{
                            $this->msg->showMessage("Le mot de passe est incorect","admin","error");
                            $message= "Le mot de passe est incorrect";
                        }
                    }
                }else{
                    $this->msg->showMessage("Veuillez saisir tous les champs","admin","error");
                } 
            }
            require "www/layout.phtml";
            
        }

        //ADMIN METHODS
        // return boolean true if admin is connected or false
        public function isAdmin():bool{
            if(isset($_SESSION['emailAdmin'])){
                return true;
            }else{
                return false;
            }
        }  
        
        // admin disconnect
        public function deconnect():void{
            $_SESSION= array();
            session_destroy();
            header("location:index.php");
            exit();
        }
    }