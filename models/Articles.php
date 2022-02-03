<?php
    class Articles
    {

        // PROPERTIES
        private  $database;
        private  $bdd;

        // CONSTRUCTOR
        public function __construct(){
            $this->database = new Database();
            $this->bdd      = $this->database->getConnectBdd();
        }

        //METHODS

        /*====SELECT====*/
        // Select an article buy ID
        public function getArticleById(int $idArticle):array{
            $query = $this->bdd->prepare('
            SELECT id_article, titre, contenu,  DATE_FORMAT(date,"%e/%m/%Y %kh%i") AS dateformat,image, nom,prenom,nom_cat,(categories.id_categorie)AS id_cat
            FROM articles
            INNER JOIN categories ON articles.id_categorie = categories.id_categorie
            INNER JOIN admin ON articles.id_auteur = admin.id_admin
            WHERE id_article=?');
    
            $query->execute([$idArticle]);
            $oneArticle = $query->fetch();
            return $oneArticle;
        }

        // Select list of articles
        public function getArticlesList():array{
            $query = $this->bdd->prepare('
            SELECT id_article, titre, contenu,  DATE_FORMAT(date,"%e/%m/%Y %kh%i") AS dateformat,image, nom,prenom,nom_cat
            FROM articles
            INNER JOIN categories ON articles.id_categorie = categories.id_categorie
            INNER JOIN admin ON articles.id_auteur = admin.id_admin
            ORDER BY id_article DESC
            LIMIT 30'
            );

            $query->execute();
            $articlesList = $query->fetchAll();
            return $articlesList;
        }
        // Select articles for homepage
        public function getArticlesHomepage():array{
            $query = $this->bdd->prepare('
            SELECT id_article, titre, DATE_FORMAT(date,"%e/%m/%Y %kh%i") AS dateformat ,image,nom_cat
            FROM articles
            INNER JOIN categories ON articles.id_categorie = categories.id_categorie
            ORDER BY id_article DESC
            LIMIT 8
            ');

            $query->execute();
            $articleForHome = $query->fetchAll();
            return $articleForHome;
        }
        
        // Select articles categories
        public function getArticleCategories():array{
            $query=$this->bdd->prepare('
            SELECT id_categorie, nom_cat 
            FROM categories'
            );
            
            $query->execute();
            $articleCategories = $query->fetchAll();
            return $articleCategories;
        }

        //ADMIN METHODS

        /*====INSERT INTO====*/

        // add a new article
        public function addNewArticle(string $articleTitre,string $articleContenu,string $articleAuteur,string $articleCategory,string $uniqueName="default.jpg" ):bool{
            $query = $this->bdd->prepare('
            INSERT INTO articles(titre, contenu, date, id_auteur, id_categorie, image) 
            VALUES (?,?,NOW(),?,?,?)');

            $test = $query->execute([$articleTitre,$articleContenu,$articleAuteur,$articleCategory,$uniqueName ]);
            return $test;
        }

        /*====UPDATE====*/

        //Update article with image
        public function upDateArticleWithImage(string $articleTitre,string $articleContenu,string $articleCategory,string $uniqueName,int $idarticleToUpDate):bool{
            $query = $this->bdd->prepare('
            UPDATE articles 
            SET titre=?,contenu=?,id_categorie=?,image=?
            WHERE id_article=?
            ');

            $test = $query->execute([$articleTitre,$articleContenu,$articleCategory,$uniqueName,$idarticleToUpDate]);
            return $test;
        }
        
        //Update article with default image
        public function upDateArticleWithoutImage(string $articleTitre,string $articleContenu,string $articleCategory,int $idarticleToUpDate):bool{
            $query = $this->bdd->prepare('
            UPDATE articles 
            SET titre=?,contenu=?,id_categorie=?
            WHERE id_article=?
            ');

            $test = $query->execute([$articleTitre,$articleContenu,$articleCategory,$idarticleToUpDate]);
            return $test;
        }

        /*====DELETE====*/

        // delete article by id
        public function deleteArticleById(int $idArticleToDelete):bool{
                $query = $this->bdd->prepare('
                DELETE FROM articles
                WHERE id_article=?
                ');

                $test = $query->execute([$idArticleToDelete]);
                return $test;
        }
    }