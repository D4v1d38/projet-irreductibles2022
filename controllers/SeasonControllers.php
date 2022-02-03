<?php
    require 'models/Season.php';

    class SeasonControllers
    {

        private $season;

        public function __construct(){
            $this->season   = new Season();
            $this->player   = new Player();
            $this->connect  = new AdminControllers();
            $this->msg      = new Functions();
        }

        //get all teams
        public function seasonsManager():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
                $playersManage = $this->season->getallTeams();
                $template = "www/admin/seasonsManagement";
                require "www/layout.phtml";
        }

        //get all seasons
        public function allSeasons():array{
            $seasonList = $this->season->getAllSeasons(); 
            return $seasonList;
        }

        //team by a season
        public function team():void{
            if(array_key_exists('id_saison',$_GET) && is_numeric($_GET['id_saison'])){
                $idSaison       = $_GET['id_saison'];
                $teamplayers    = $this->season->getTeam($idSaison);
                $teamStatistics = $this->season->getTeamStatistic($idSaison); 
            }else{
                header("location:index.php");
                exit();
            }
            $template ="www/team/team";
            require "www/layout.phtml";
        }

        // add a player into a season
        public function playerToSeason():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(!empty($_POST)){
                if(isset($_POST['season'],$_POST['player']) && !empty($_POST['season']) && !empty($_POST['player'])){
                    $idSaison = $_POST['season'];
                    $idJoueur = $_POST['player'];

                    $isPlayer = $this->season->getIdPlayerinTeam($idJoueur,$idSaison);

                    if(!$isPlayer){
                        $add = $this->season->playerToTeam($idSaison,$idJoueur);
                    }else{
                        $this->msg->showMessage("Le Joueur existe déja dans cet effectif","player_to_season","error");
                    }

                    //Gestion de l'affichage des messages d'infos.
                    if($add==true){
                        $this->msg->showMessage("joueur ajouté avec succes!","player_to_season","success");
                    }else{
                        $this->msg->showMessage("une erreur est survenue !","admin","error");
                    }
                }
            }else{
                $seasonSelect  = $this->season->getAllSeasons();
                $playerSelect  = $this->player->getAllPlayers();
            }
                
            $template="www/admin/addPlayerToSeason";
            require "www/layout.phtml";
        }
            

        // take off player of a season 
        public function removePlayerOfSeason():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
            
            if(isset($_POST['remove_player_id_season'], $_POST['remove_player_id_player']) &&
                is_numeric($_POST['remove_player_id_season']) && is_numeric($_POST['remove_player_id_player']) ){
                    
                $idSaison   = $_POST['remove_player_id_season'];
                $idJoueur   = $_POST['remove_player_id_player'];
                $remove = $this->season->removePlayerOfTeam($idJoueur,$idSaison);

                if($remove == true){
                    $this->msg->showMessage("Joueur retiré de l'effectif avec succès !","season_manager","success");
                }else{
                    $this->msg->showMessage("Une erreur est survenue lors de la suppression du joueur de l'effectif!","admin","error");   
                }
            }else{
                $this->msg->showMessage("Une erreur est survenue !","admin","error");                  
            }
        }
    }