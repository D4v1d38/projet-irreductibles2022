<?php

class Request
{

    //Propriétés
    public function __construct(){
        $this->database = new Database();
        $this->bdd      = $this->database->getConnectBdd();
    }

    // select all messages
    public function getRequestList():array{
        $query = $this->bdd->prepare('
        SELECT id_contact, date, prenom, nom, telephone, email, objet, message
        FROM contacts 
        ');

        $query->execute();
        $list = $query->fetchAll();
        return $list;
    }

        // Select a request by id
        public function getRequestById(int $idRequest):array{
            $query = $this->bdd->prepare('
            SELECT id_contact, date, prenom, nom, telephone, email, objet, message 
            FROM contacts 
            WHERE id_contact = ?
            ');
    
            $query->execute([$idRequest]);
            $detailRequest = $query->fetch();
            return $detailRequest;
        }

    // add request 
    public function addRequest(string $firstname,string $name,string $email,string $object,string $content,string $tel):bool{
        $query = $this->bdd->prepare('
        INSERT INTO contacts(date,prenom, nom, email,objet, message,telephone) 
        VALUES (NOW(),?,?,?,?,?,?)
        ');

        $addRequest = $query->execute([$firstname,$name,$email,$object,$content,$tel]);
        return $addRequest;
    }

    // Delete request
    public function deleteRequestById(int $idRequest):bool{
        $query = $this->bdd->prepare('
        DELETE FROM contacts 
        WHERE id_contact=?');

        $deleteMsg = $query->execute([$idRequest]);
        return $deleteMsg;
    }
}