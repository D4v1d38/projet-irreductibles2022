<?php

    class Games
    {
        // PROPRIETES
        private $database;
        private $bdd;

        // CONSTRUCTOR
        public function __construct(){
            $this->database = new Database();
            $this->bdd      = $this->database->getConnectBdd();
        }

        //METHODES

        /*===SELECT ===*/
        //select game by id
        public function getGameById(int $idMatch):array{
            if(array_key_exists('id_game',$_GET) && is_numeric($_GET['id_game'])){
                $query = $this->bdd->prepare('
                SELECT id, date_match,competition,statut,id_teamDom, dom.formation AS equipeDom, dom.ville AS villeDom ,scoreDom, scoreExt,id_teamExt,ext.formation AS equipeExt , ext.ville AS villeExt
                FROM rencontres
                INNER JOIN equipes AS dom ON dom.id_equipe = rencontres.id_teamDom
                INNER JOIN equipes AS ext ON ext.id_equipe = rencontres.id_teamExt
                WHERE id=?
                ');
                
                $query->execute([$idMatch]);
                $game = $query->fetch(); 
                return $game;
            }else{
                header('location:index.php');
                exit();
            }
        }

        //select game of the day
        public function getGameOfTheDay(): ?array{
            $query = $this->bdd->prepare('
            SELECT date_match, DATE_FORMAT(date_match, "%d/%m/%Y" ) AS dateGame,competition,statut, dom.formation AS equipeDom, dom.ville AS villeDom ,scoreDom, scoreExt,ext.formation AS equipeExt , ext.ville AS villeExt
            FROM rencontres
            INNER JOIN equipes AS dom ON dom.id_equipe = rencontres.id_teamDom
            INNER JOIN equipes AS ext ON ext.id_equipe = rencontres.id_teamExt
            WHERE date_match = DATE(NOW())
            ');
            
            $query->execute();
            $gameOfTheDay = $query->fetch(); 

            return $gameOfTheDay ?:null; 
            
        }
        
        //select latest game
        public function getLatestGame(): ?array{
            $query = $this->bdd->prepare('
            SELECT date_match, DATE_FORMAT(date_match, "%d/%m/%Y" ) AS dateGame,competition,statut, dom.formation AS equipeDom, dom.ville AS villeDom ,scoreDom, scoreExt,ext.formation AS equipeExt , ext.ville AS villeExt
            FROM rencontres
            INNER JOIN equipes AS dom ON dom.id_equipe = rencontres.id_teamDom
            INNER JOIN equipes AS ext ON ext.id_equipe = rencontres.id_teamExt
            WHERE unix_timestamp(date_match) < unix_timestamp(DATE(NOW()))
            ORDER BY unix_timestamp(date_match) DESC
            LIMIT 1
            ');

            $query->execute();
            $latestGame = $query->fetch();
            return $latestGame ?: null;
        }

        //select next game
        public function getNextGame(): ?array{
            $query = $this->bdd->prepare('
            SELECT date_match, DATE_FORMAT(date_match, "%d/%m/%Y" ) AS dateGame,competition,statut, dom.formation AS equipeDom, dom.ville AS villeDom ,scoreDom, scoreExt,ext.formation AS equipeExt , ext.ville AS villeExt
            FROM rencontres
            INNER JOIN equipes AS dom ON dom.id_equipe = rencontres.id_teamDom
            INNER JOIN equipes AS ext ON ext.id_equipe = rencontres.id_teamExt
            WHERE unix_timestamp(date_match) >= unix_timestamp(DATE(NOW()))
            ORDER BY unix_timestamp(date_match)
            LIMIT 1
            ');

            $query->execute();
            $nextGame = $query->fetch();
            return $nextGame ?: null;
        }

        //Select all games
        public function getAllGames(): array{
            $query = $this->bdd->prepare('
            SELECT id ,DATE_FORMAT(date_match, "%d/%m/%Y" ) AS dateGame,competition,statut, dom.formation AS equipeDom, dom.ville AS villeDom ,scoreDom, scoreExt,ext.formation AS equipeExt , ext.ville AS villeExt
            FROM rencontres
            INNER JOIN equipes AS dom ON dom.id_equipe = rencontres.id_teamDom
            INNER JOIN equipes AS ext ON ext.id_equipe = rencontres.id_teamExt
            ORDER BY unix_timestamp(date_match) ASC
            ');

            $query->execute();
            $allGames = $query->fetchAll();
            return $allGames;
        }

        //Select all teams
        public function getAllTeams():array{
            $query=$this->bdd->prepare('
            SELECT id_equipe, ville
            FROM equipes 
            ');

            $query->execute();
            $teams =$query->fetchAll();
            return $teams;
        }

        /*===INSERT INTO ===*/
        // Add a game
        
        public function insertAGame(string $gameDay,string $competition,string $statut,string $teamHome,string $scoreHome,string $scoreOut,string $teamOut ):bool{

            $query = $this->bdd->prepare('
            INSERT INTO rencontres(date_match, competition, statut, id_teamDom, scoreDom, scoreExt, id_teamExt)
            VALUES (?,?,?,?,?,?,?)
            ');

            $newGame=$query->execute([ $gameDay, $competition, $statut, $teamHome, $scoreHome, $scoreOut, $teamOut]);
            return $newGame;
        }

        /*===UPDATE ===*/
        // Update a game

        public function upDateAGame(string $gameDay,string $competition,string $statut, string $teamHome, string $scoreHome,string $scoreOut,string  $teamOut ,string $idGame):bool{

            $query = $this->bdd->prepare('
            UPDATE rencontres
            SET date_match =?,competition=?,statut=?,id_teamDom=?,scoreDom=?,scoreExt=?,id_teamExt=?
            WHERE id=?
            ');

            $updateGame = $query->execute([$gameDay,$competition,$statut,$teamHome, $scoreHome, $scoreOut, $teamOut,$idGame ]);
            return $updateGame;
        }

        /*===DELETE ===*/
        // Delete a game

        public function deleteGameById(int $idGame):bool{
            $query = $this->bdd->prepare('
            DELETE FROM rencontres 
            WHERE id=?
            ');

            $deleteGame = $query->execute([$idGame]);
            return  $deleteGame;
        }
    }