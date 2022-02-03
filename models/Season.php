<?php

    class Season
    {
        // PROPERTIES
        private $database;
        private $bdd;

        // CONSTRUCTOR
        public function __construct(){
            $this->database = new Database();
            $this->bdd      = $this->database->getConnectBdd();
        }

        //METHODS

        //select all seasons 
        public function getAllSeasons():array{
            $query = $this->bdd->prepare('
            SELECT id_saison,annee 
            FROM saison');
            
            $query->execute();
            $seasons = $query->fetchAll(); 
            return $seasons;
        }

        // Select players by id ans season 
        public function getIdPlayerinTeam(int $idJoueur,int $idSaison):array{
            $query = $this->bdd->prepare('
            SELECT id_joueur
            FROM effectif 
            WHERE id_joueur=? && id_saison=?');
            
            $query->execute([$idJoueur,$idSaison]);
            $players = $query->fetchAll(); 
            return $players;
        }

        //select team by season
        public function getTeam(int $idSaison):array{
            $query = $this->bdd->prepare('
            SELECT annee, prenom,nom, numero, DATE_FORMAT(date_de_naissance,"%e/%m/%y") AS naissance,(YEAR(NOW())-YEAR(date_de_naissance))AS age, nationalite, poste,taille,poids, photo
            FROM effectif
            INNER JOIN saison ON effectif.id_saison = saison.id_saison
            INNER JOIN joueurs ON joueurs.id_joueurs=effectif.id_joueur
            WHERE effectif.id_saison=?
            ORDER BY numero');
        
            $query->execute([$idSaison]);
            $team = $query->fetchAll();
            return $team;
        }
        
        //recuperation des statistic de l'effectif 
        public function getTeamStatistic(int $idSaison): ?array{
            $query=$this->bdd->prepare('
            SELECT id_saison, AVG(taille)AS avgHeight, AVG(poids)AS avgWeight,COUNT(id_effectif)AS nbPlayers
            FROM joueurs
            INNER JOIN effectif ON joueurs.id_joueurs = effectif.id_joueur
            WHERE id_saison=?
            GROUP BY id_saison');
        
            $query->execute([$idSaison]);
            $teamStatistic = $query->fetch();
            return $teamStatistic ?: null ;
        }
        
        // select all teams
        public function getallTeams():array{
            $query = $this->bdd->prepare('
            SELECT id_effectif, effectif.id_saison, id_joueur,annee,nom,prenom,numero FROM effectif 
            INNER JOIN joueurs ON effectif.id_joueur = joueurs.id_joueurs
            INNER JOIN saison ON effectif.id_saison = saison.id_saison
            ORDER BY id_effectif
            ');
        
            $query->execute();
            $allTeams = $query->fetchAll();
            return $allTeams;
        }

        //add player into team
        public function playerToTeam(int $idSaison,int $idJoueur):bool{
            $query = $this->bdd->prepare('
                INSERT INTO effectif(id_saison, id_joueur)
                VALUES (?,?)
                ');
        
            $addPlayerToTeam = $query->execute([$idSaison,$idJoueur]);
                return $addPlayerToTeam;
        }

        //take off player to a season
            public function removePlayerOfTeam(int $idJoueur,int $idSaison):bool{
                $query = $this->bdd->prepare('
                DELETE FROM effectif 
                WHERE id_joueur=? && id_saison = ? 
                ');
        
                $remove = $query->execute([$idJoueur,$idSaison]);
                return $remove;
            }
    }