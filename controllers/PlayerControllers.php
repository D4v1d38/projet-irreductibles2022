<?php
    require 'models/Player.php';

    class PlayerControllers
    {

        private $player;
        private bool $addDefault = false;
        private bool $add        = false;
        

        public function __construct(){
            $this->player       = new Player();
            $this->connect      = new AdminControllers();
            $this->seasons      = new SeasonControllers();
            $this->image        = new Functions();
            $this->msg          = new Functions();
        }

        //select all players
        public function allPLayers():void{
            $allPlayers = $this->player->getAllPlayers();
            $template="www/team/team";
            require "www/layout.phtml";    
        }

        //ADMIN METHODS
        //select players for manage
        public function playerManager():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
                
                $playersManage = $this->player->getAllPlayers();
                $template = "www/admin/playersManagement";
                require "www/layout.phtml";
        }
        
        //add a new player
        public function createPlayer():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
                
            $seasonSelect = $this->seasons->allSeasons();

            if(!empty($_POST)){
                if(isset($_POST['firstname'],$_POST['name'],$_POST['number'],$_POST['birthdate'],$_POST['nation'],$_POST['height'],$_POST['weight'],$_POST['position'])){
                        
                    if(!empty($_POST['firstname']) && !empty($_POST['name']) && !empty($_POST['birthdate']) 
                        && !empty($_POST['nation']) && !empty($_POST['position']) && !empty($_POST['height']) && !empty($_POST['weight'])){
                        
                        $error          = false;
                        $regName        = "/^[a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+([-'\s][a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+)?$/";
                        $regNation      = "/[a-zA-Z-çéèêàiï]/";
                        
                        $firstname      = trim($_POST['firstname']);
                        $familyName     = trim($_POST['name']);
                        $number         = trim($_POST['number']);
                        $birthdate      = trim($_POST['birthdate']);
                        $nation         = trim($_POST['nation']);
                        $height         = trim($_POST['height']);
                        $weight         = trim($_POST['weight']);
                        $position       = trim($_POST['position']);
                        
                        //identity check
                        if(!preg_match($regName,$firstname) || !preg_match($regName,$familyName)) $error = true;
                        
                        //numeric items checks 
                        if(!is_numeric($number) || !is_numeric($height) || !is_numeric($weight) ) $error = true;
                        
                        //nation check
                        if(!preg_match($regNation,$nation)) $error = true;
                        
                        //image check        
                        if(isset($_FILES['image']) && $_FILES["image"]["error"] == 0 ){
                            $filesImage         = $_FILES["image"];
                            $destinationFile    = 'www/img/players/';
                            $playerImage        = $this->image->imageControls($filesImage,$destinationFile);

                            if(isset($playerImage) && $error == false){
                                $add = $this->player->addplayer($firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight, $playerImage);
                            }
                        }else if($error==false){
                                $addDefault = $this->player->addplayer($firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight);
                        }
                    }else{
                        $this->msg->showMessage("Tous les champs doivent être remplis","create_player","error");
                    }
                }else{
                    $this->msg->showMessage("Une erreur est survenu lors du traitement du formulaire","admin","error");
                }
                
                //information message 
                if($add===true){
                    $this->msg->showMessage("joueur ajouté avec succès!","admin","success");
                }else if($addDefault==true){
                    $this->msg->showMessage("joueur ajouté avec succès avec l'image par défaut!","admin","info");
                }else{
                    $this->msg->showMessage("Une erreur est survenue !","admin","error");
                }
            }else{
                $template = "www/admin/createPlayer";
                require "www/layout.phtml";
            }
        }

        // update a player
        public function upDatePlayer():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(!empty($_POST)){
                //form datas check
                if(isset($_POST['firstname'],$_POST['name'],$_POST['number'],$_POST['birthdate'],$_POST['nation'],$_POST['height'],$_POST['weight'],$_POST['position']) &&
                    !empty($_POST['firstname']) && !empty($_POST['name']) && !empty($_POST['birthdate']) && !empty($_POST['nation']) 
                        && !empty($_POST['position']) && !empty($_POST['height']) && !empty($_POST['weight'])){
                    
                    $error          = false;
                    $regName        = "/^[a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+([-'\s][a-zA-ZéèîïÉÈÎÏ][a-zéèêàçîï]+)?$/";
                    $regNation      = "/[a-zA-Z-çéèêàiï]/";
                    
                            
                    $idPlayer       = trim($_POST['id_player']);
                    $firstname      = trim($_POST['firstname']);
                    $familyName     = trim($_POST['name']);
                    $number         = trim($_POST['number']);
                    $birthdate      = trim($_POST['birthdate']);
                    $nation         = trim($_POST['nation']);
                    $height         = trim($_POST['height']);
                    $weight         = trim($_POST['weight']);
                    $position       = trim($_POST['position']);
                    
                    //identity check
                    if(!preg_match($regName,$firstname) || !preg_match($regName,$familyName)) $error = true;
                        
                    //numeric items checks 
                    if(!is_numeric($number) || !is_numeric($number) || !is_numeric($height) || !is_numeric($weight) ) $error = true;
                        
                    //nation check 
                    if(!preg_match($regNation,$nation)) $error = true;

                    if(isset($_FILES['image']) && $_FILES["image"]["error"] ==0 ){
                        $filesImage = $_FILES["image"];
                        $destinationFile = 'www/img/players/';
                        $playerImage=$this->image->imageControls($filesImage,$destinationFile);

                        if(isset($playerImage) && $error == false){
                            $add = $this->player->upDatePlayerWithimage($firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight, $playerImage,$idPlayer);
                        }
                    }else if($error == false){
                        $addDefault = $this->player->upDatePlayerWithoutimage($firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight,$idPlayer);
                    }                                              
                }else{
                    $this->msg->showMessage("Tous les champs doivent être remplis","admin","error");
                }
                    
                //information message
                if($add==true){
                    $this->msg->showMessage("joueur modifié avec succes!","admin","success");
                }else if($addDefault==true){
                    $this->msg->showMessage("joueur modifié avec succes avec la photo d'origine !","admin","info");
                }else{
                    $this->msg->showMessage("une erreur est survenue !","admin","error");
                }

            }else{
                if(array_key_exists('id_player',$_GET) && is_numeric($_GET['id_player'])){
                    $idPlayer = $_GET['id_player'];
                    $playerToUpdate = $this->player->getPlayerById($idPlayer);
                    $template = "www/admin/upDatePlayer";
                    require "www/layout.phtml";
                }else{
                    header('location:index.php');
                    exit();
                }
            }
        }

        // Delete a player
        public function deletePlayer():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(isset($_POST['delete_player']) && is_numeric($_POST['delete_player'])){
                $idJoueur       = $_POST['delete_player'];
                $photoPlayer    = $_POST['photo_player'];
                $delete         = $this->player->deletePlayerById($idJoueur);
                    
                if($delete == true){
                    // delete image player if image is not default image
                    if($photoPlayer != "portrait.png") unlink('./www/img/players/'.$photoPlayer);
                    $this->msg->showMessage("Joueur supprimé avec succès !","player_manager","success");
                }else{
                    $this->msg->showMessage("Une erreur est survenue lors de la suppression du joueur !","player_manager","error");  
                }
            }else{
                header('location:index.php');
                exit();
            }
        }
    }