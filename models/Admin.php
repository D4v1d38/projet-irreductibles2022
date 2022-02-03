<?php

    class Admin
    {
        
        // PROPERTIES
        private $database;
        private $bdd;
        
        // CONSTRUCTOR
        
        public function __construct(){
            
            $this->database = new Database();
            $this->bdd      = $this->database->getConnectBdd();
            
        }
        
        // METHODS

        /*====SELECT====*/
        // select an admin by mail
        public function getAdminByEmail(string $email): ?array{
            $query = $this->bdd->prepare('
            SELECT id_admin, nom, prenom, email, role, password,niveau_admin
            FROM admin
            INNER JOIN fonctions ON admin.id_fonction = fonctions.id_fonction
            WHERE email = ?');

            $query->execute([$email]);
            $admin = $query->fetch();
            
            return $admin ?: null;
        }
        
    }