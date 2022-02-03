<?php

    //models require
    require 'models/Articles.php';

    class ArticlesControllers
    {
        private object $articles;
        private bool $addDefault = false;
        private bool $add = false;

        public function __construct(){
            $this->articles = new Articles();
            $this->connect  = new AdminControllers();
            $this->msg      = new Functions();
            $this->image    = new Functions();
        }

        // Select article by Id
        public function articleById():void{
            if(array_key_exists('id_article',$_GET) && is_numeric($_GET['id_article'])){
                $idArticle = $_GET['id_article'];
                $oneArticle = $this->articles->getArticleById($idArticle);
                $template="www/articles/article";
                require "www/layout.phtml";
            }else{
                header('location:index.php');
                exit();
            }
        }
        
        // Select articles for homepage
        public function articlesForHome():void{
            $homeArticle = $this->articles->getArticlesHomepage();
            $dataSlide=[];
            foreach($homeArticle as $item){
                array_push($dataSlide, $item);
            }
            $template="www/home";
            require "www/layout.phtml";
        }

        // Select list of articles
        public function articlesList():void{
            $articlesList = $this->articles->getArticlesList();
            $template = "www/articles/listeDesArticles";
            require "www/layout.phtml";
        }
        
        //ADMIN METHODS
        //Select list of articles for adminpage
        public function articleManager():void{
            if( !$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            $articlesManage = $this->articles->getArticlesList();
            $template="www/admin/articlesManagement";
            require "www/layout.phtml";
            
        }
        
        // Delete article by id
        public function deleteArticle():void{
            if( !$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }

            if(isset($_POST['delete_article']) && is_numeric($_POST['delete_article'])){
                $idArticleToDelete = $_POST['delete_article'];
                $imageToDelete = $_POST['delete-image'];
                $delete = $this->articles->deleteArticleById($idArticleToDelete);

                if($delete == true){
                    if($imageToDelete != 'default.jpg') unlink('./www/img/articles/'.$imageToDelete);
                    $this->msg->showMessage("Article supprimé avec succès !","article_manager","success");
                }else{
                    $this->msg->showMessage("Une erreur est survenue !","admin","error");
                }
            }else{
                header('location:index.php');
                exit();
            }
        }
        
        // Create a new article
        public function createArticle():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
            
            $categoriesList = $this->articles->getArticleCategories();
            
            if(!empty($_POST)){
                //form datas chek
                if(isset($_POST["article_titre"],$_POST["article_contenu"],$_POST["article_auteur"],$_POST["article_category"]) 
                && !empty($_POST["article_titre"]) && !empty($_POST["article_contenu"]) && !empty($_POST["article_auteur"]) && !empty($_POST["article_category"])){

                    $articleTitre       = $_POST["article_titre"];
                    $articleContenu     = $_POST["article_contenu"];
                    $articleAuteur      = $_POST["article_auteur"];
                    $articleCategory    = $_POST["article_category"];
                    $idAuteur           = $_SESSION["idAdmin"];
                        
                    //image check
                    if(isset($_FILES["article_image"]) && $_FILES["article_image"]["error"] == 0){
                            
                        $filesImage         = $_FILES["article_image"];
                        $destinationFile    = 'www/img/articles/';
                        $articleImage       = $this->image->imageControls($filesImage,$destinationFile);

                        if(isset($articleImage)){
                        
                            $add = $this->articles->addNewArticle($articleTitre,$articleContenu,$idAuteur,$articleCategory,$articleImage);
                        }
                    }else{
                        $addDefaut = $this->articles->addNewArticle($articleTitre,$articleContenu,$idAuteur,$articleCategory);
                    }
                }else{
                    $this->msg->showMessage("Les champs sont vides !","create_article","error");
                }

                if($add == true){
                    $this->msg->showMessage("article ajouté avec succes!","admin","success");
                }else if($addDefaut==true){
                    $this->msg->showMessage("article ajouté avec succes avec l'image par défaut!","admin","info");
                }else{
                    $this->msg->showMessage("une erreur est survenue !","admin","error");
                }
            }else{
                $template = "www/admin/createArticle";
                require "www/layout.phtml";
            }
            
        }
        
        // Update a new article
        public function upDateArticle():void{
            if(!$this->connect->isAdmin() === true){
                header('location:index.php');
                exit();
            }
                
            $categoriesList = $this->articles->getArticleCategories();
            
            if(!empty($_POST)){
                
                //form datas chek
                if(isset($_POST["article_titre"],$_POST["article_contenu"],$_POST["article_auteur"],$_POST["article_category"]) 
                && !empty($_POST["article_titre"]) && !empty($_POST["article_contenu"]) && !empty($_POST["article_auteur"]) && !empty($_POST["article_category"])){
                    $idArticleToUpDate  = $_POST["id_article"];
                    $articleTitre       = $_POST["article_titre"];
                    $articleContenu     = $_POST["article_contenu"];
                    $articleAuteur      = $_POST["article_auteur"];
                    $idCategory         = $_POST["id_cat"];

                    //image check
                    if(isset($_FILES["article_image"]) && $_FILES["article_image"]["error"] == 0){
                        $filesImage = $_FILES["article_image"];
                        $destinationFile = 'www/img/articles/';
                            
                        $articleImage=$this->image->imageControls($filesImage,$destinationFile);

                        if(isset($articleImage)){
                            $add = $this->articles->upDateArticleWithImage($articleTitre,$articleContenu,$idCategory,$articleImage, $idArticleToUpDate);
                        }
                            
                    }else{ //if image doesn't exist, update whithout image for keep previous image
                            $addDefaut = $this->articles->upDateArticleWithoutImage($articleTitre,$articleContenu,$idCategory,$idArticleToUpDate);
                    }
                }else{
                    $this->msg->showMessage("Modification non prise en compte car les champs sont vides","admin","error");
                }
                    
                if($add==true){
                    $this->msg->showMessage("article modifié avec succes!","admin","success");
                }else if($addDefaut==true){
                    $this->msg->showMessage("article modifié avec succes avec l'image d'origine!","admin","info");
                }else{
                    $this->msg->showMessage("une erreur est survenue !","admin","error");
                }               
            }else{ // if form doesn't submit, show form complete with article selected
                if(array_key_exists('id_article',$_GET) && is_numeric($_GET['id_article'])){
                    $idArticle = $_GET['id_article'];
                    $oneArticle = $this->articles->getArticleById($idArticle);
                    $template="www/admin/upDateArticle";
                    require "www/layout.phtml";
                }
            }
        }
    }