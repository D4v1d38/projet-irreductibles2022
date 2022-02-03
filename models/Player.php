<?php

    class Player
    {
        // PROPERTIES
        private $database;
        private $bdd;

        // CONSTRUCTOR
        public function __construct(){
            $this->database = new Database();
            $this->bdd      = $this->database->getConnectBdd();
        }

        //METHODSS

        /*====SELECT====*/

        //Select players info  by id
        public function getPlayerById(int $idPlayer):array{
            $query = $this->bdd->prepare('
            SELECT id_joueurs, prenom, nom, numero, date_de_naissance, nationalite, poste, taille, poids, photo 
            FROM joueurs 
            WHERE id_joueurs =?
            ');

            $query->execute([$idPlayer]);
            $onePlayer =$query->fetch();
            return $onePlayer;
        }

        //Select all players from database
        public function getAllPlayers():array{
            $query = $this->bdd->prepare('
            SELECT id_joueurs, prenom, nom, numero, date_de_naissance, nationalite, poste, taille, poids, photo 
            FROM joueurs
            ORDER BY nom ASC ');
            
            $query->execute();
            $players = $query->fetchAll(); 
            return $players;
        }
        
        /*====INSERT INTO====*/

        //Add a player
        public function addplayer(string $firstname, string $familyName,int $number,string $birthdate,string $nation,string $position,int $height,int $weight ,string $uniqueName="portrait.png"):bool{
            $query=$this->bdd->prepare('
            INSERT INTO joueurs( prenom, nom, numero, date_de_naissance, nationalite, poste, taille, poids, photo) 
            VALUES (?,?,?,?,?,?,?,?,?)');

            $test=$query->execute([$firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight , $uniqueName]);
            return $test;
        }

        /*====UPDATE====*/

        //Update a player
        public function upDatePlayerWithimage(string $firstname,string $familyName,int $number,string $birthdate,string $nation, string $position,int $height,int $weight,string $uniqueName,int $idPlayer):bool{
            $query = $this->bdd->prepare('
            UPDATE joueurs 
            SET prenom=?,nom=?,numero=?,date_de_naissance=?,nationalite=?,poste=?,taille=?,poids=?,photo=?
            WHERE id_joueurs = ?
            ');

            $upDateplayer  = $query->execute([$firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight, $uniqueName,$idPlayer]);
            return $upDateplayer;
        }

        public function upDatePlayerWithOutimage(string $firstname,string $familyName,int $number,string $birthdate,string $nation, string $position,int $height,int $weight,int $idPlayer):bool{
            $query = $this->bdd->prepare('
            UPDATE joueurs 
            SET prenom=?,nom=?,numero=?,date_de_naissance=?,nationalite=?,poste=?,taille=?,poids=?
            WHERE id_joueurs = ?
            ');

            $upDateplayer  = $query->execute([$firstname, $familyName, $number, $birthdate, $nation, $position, $height, $weight,$idPlayer]);
            return $upDateplayer;
        }

        /*==== DELETE ====*/

        //Delete player(delete FROM "player"table and "effectif" table)
        public function deletePlayerById(int $idJoueur):bool{
            $query = $this->bdd->prepare('
            DELETE FROM effectif
            WHERE id_joueur =?
            ');

            $query->execute([$idJoueur]);

            $query2 = $this->bdd->prepare('
            DELETE FROM joueurs 
            WHERE id_joueurs = ?
            ');

            $deletePlayer2 = $query2->execute([$idJoueur]);

            return $deletePlayer2;
        }

    }