<?php

    //DataBase Connect

    class Database{

        private object $bdd;

        public function __construct(){
            try{
                $this->bdd = new PDO('mysql:host=***host***;dbname=davidrotolo_irreductibles;charset=utf8',"davidrotolo","***password***");
            }
            catch(Exception $e){
                die("message d'erreur : " .$e->getMessage());
            }
        }

        public function getConnectBdd():object{
            return $this->bdd;
        }
    }
