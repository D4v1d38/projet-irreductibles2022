<?php
    require 'models/Games.php';

    class GamesControllers
    {

        private $games;
        
        public function __construct(){
            $this->games       = new Games();
            $this->connect     = new AdminControllers();
            $this->msg         = new Functions();
        }

        public function gameAjax():void{
            $gameDayAjax    = $this->games->getGameOfTheDay();
            require "www/games/gameDay.phtml";
        }

        public function calendar():void{
            $allMatchs  = $this->games->getAllGames();
            $lastGame   = $this->games->getLatestGame();
            $nextGame   = $this->games->getNextGame();

            $template="www/games/calendar";
            require "www/layout.phtml";   
        }

        public function articleById():void{
            if(array_key_exists('id_game',$_GET) && is_numeric($_GET['id_game'])){
                $idGame = $_GET['id_game'];
                $oneGame= $this->games->getGameById($idGame);
                $template="www/admin/upDateGame";
                require "www/layout.phtml";
            }else{
                header('location:index.php');
                exit();
            }
        }

        // ADMIN
        public function gamesManager():void{
            if( !$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            $gamesManage = $this->games->getAllGames();
            $template="www/admin/gamesManagement";
            require "www/layout.phtml";
        }

        public function createGame():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(!empty($_POST)){
                if(isset($_POST['competition'],$_POST['date_match'],$_POST['domicile'],$_POST['score_dom'],$_POST['exterieur'],$_POST['score_ext'],$_POST['statut'])){
                    
                    $error = false;
                    $regDate = "([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))";

                    if(!empty($_POST['competition']) && !empty($_POST['domicile']) && !empty($_POST['exterieur'])&&!empty($_POST['date_match'])){

                        $competition    = trim($_POST['competition']);
                        $teamHome       = trim($_POST['domicile']);
                        $teamOut        = trim($_POST['exterieur']);
                        $statut         = trim($_POST['statut']);
                        
                        //Equipes : contrôle que les équipes sont différentes
                        if($teamHome === $teamOut){
                            $this->msg->showMessage("L'équipe domicile et extérieur ne peuvent pas être identiques","create_game","error");
                            $error = true;
                        } 

                        //Score: contrôle si le score est soit vide OU si le champ est rempli que la valeur soit un nombre
                        if(!empty($_POST['score_dom']) && !is_numeric($_POST['score_dom']) || !empty($_POST['score_ext']) && !is_numeric($_POST['score_ext'])){
                            $this->msg->showMessage("le score doit être un nombre","create_game","error");
                            $error = true;
                        }else{
                            $scoreHome  = trim($_POST['score_dom']);
                            $scoreOut   = trim($_POST['score_ext']);
                        }

                        //contrôle sur la date
                        if(!preg_match($regDate,$_POST['date_match'])){
                            $this->msg->showMessage("Le format de la date n'est pas valide","create_game","error");
                            $error = true;
                        }else{
                            $gameDay = trim($_POST['date_match']);
                        }

                    }else{
                            $this->msg->showMessage("Les champs dates, compétitions, et équipes doivent être renseignés","create_game","error");
                            $error === true;
                    }
                    
                    if($error===false){
                        $test = $this->games->insertAGame( $gameDay,$competition,$statut,$teamHome, $scoreHome, $scoreOut, $teamOut);
                        $this->msg->showMessage("rencontre ajoutée avec succès","create_game","success");
                    }else{
                        $this->msg->showMessage("erreur lors du traitement du formulaire","admin","error");
                    }
                }
            }else{
                $teams = $this->games->getAllTeams();
                $template = "www/admin/createGame";
                require "www/layout.phtml";
            }
        }

        public function upDateGame():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(!empty($_POST)){
                if(isset($_POST['competition'],$_POST['date_match'],$_POST['domicile'],$_POST['score_dom'],$_POST['exterieur'],$_POST['score_ext'],$_POST['statut'],$_POST['id_game'])){

                    $error = false;
                    $regDate = "([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))";

                    if(!empty($_POST['competition']) && !empty($_POST['domicile']) && !empty($_POST['exterieur']) &&!empty($_POST['date_match'])){

                        $competition    = trim($_POST['competition']);
                        $teamHome       = trim($_POST['domicile']);
                        $teamOut        = trim($_POST['exterieur']);
                        $statut         = trim($_POST['statut']);
                        $idGame         = $_POST['id_game'];

                        //Equipes : contrôle que les équipes sont différentes
                        if($teamHome === $teamOut){
                            $this->msg->showMessage("L'équipe domicile et extérieur ne peuvent pas être identiques","create_game","error");
                            $error = true;
                        } 

                        //Score: contrôle si le score est soit vide OU si le champ est rempli que la valeur soit un nombre
                        if(!empty($_POST['score_dom']) && !is_numeric($_POST['score_dom']) || !empty($_POST['score_ext']) && !is_numeric($_POST['score_ext'])){
                            $this->msg->showMessage("le score doit être un nombre","create_game","error");
                            $error = true;
                        }else{
                            $scoreHome  = trim($_POST['score_dom']);
                            $scoreOut   = trim($_POST['score_ext']);
                        }

                        //contrôle que les équipes sont différentes
                        if(!preg_match($regDate,$_POST['date_match'])){
                            $this->msg->showMessage("Le format de la date n'est pas valide","create_game","error");
                            $error = true;
                        }else{
                            $gameDay = trim($_POST['date_match']);
                        }
                        
                    }else{
                        $this->msg->showMessage("Les champs compétitions et équipes doivent être renseignés","create_game","error");
                        $error === true;
                    }
                    
                    if($error===false){
                        $this->games->upDateAGame( $gameDay,$competition,$statut,$teamHome, $scoreHome, $scoreOut, $teamOut, $idGame);
                        $this->msg->showMessage("rencontre mise à jour  avec succès","game_manager","success");
                    }else{
                        $this->msg->showMessage("erreur lord du traitement du formulaire","admin","error");
                    }
                }
            }else{
                if(array_key_exists('id_game',$_GET) && is_numeric($_GET['id_game'])){
                    $idMatch = $_GET['id_game'];
                    $teams = $this->games->getAllTeams();
                    $oneGame = $this->games->getGameById($idMatch);
                    $template = "www/admin/upDateGame";
                    require "www/layout.phtml";
                }
            }
        }

        public function deleteGame():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(isset($_POST['delete_game']) && is_numeric($_POST['delete_game'])){
                $idGameToDelete = $_POST['delete_game'];
                
                $delete = $this->games->deleteGameById($idGameToDelete);
                
                if($delete === true){
                    $this->msg->showMessage("rencontre supprimée  avec succès","game_manager","success");
                }else{
                    $this->msg->showMessage("un problème est survenu lors de la suppression de la rencontre","game_manager","error");
                }
            }
        }
    }