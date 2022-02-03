<?php
declare(strict_types=1);
session_start();

    //Functions
    require "functions/Functions.php";

    //BDD
    require "config/Database.php";

    //Controllers
    require "controllers/ArticlesControllers.php";
    require "controllers/PlayerControllers.php";
    require "controllers/AdminControllers.php";
    require "controllers/SeasonControllers.php";
    require "controllers/RequestControllers.php";
    require "controllers/PagesControllers.php";
    require "controllers/GamesControllers.php";

    //Controllers Instances
    $articleControllers     = new ArticlesControllers();
    $PlayerControllers      = new PlayerControllers();
    $adminControllers       = new AdminControllers();
    $seasonControllers      = new SeasonControllers();
    $requestControllers     = new RequestControllers();
    $pagesControlers        = new PagesControllers();
    $gamesControllers       = new GamesControllers();

    // actions and templates display
    if(array_key_exists('action',$_GET)){
        
        switch($_GET['action']){
            case "liste_article" :
                $articleControllers->articlesList();
            break;
            case "article" : 
                $articleControllers->articleById();
            break;
            case "team" :
                $seasonControllers->team();
            break;
            case "create_player" :
                $PlayerControllers->createPlayer();
            break;
            case "update_player" :
                $PlayerControllers->upDatePlayer();
            break;
            case "delete_player" :
                $PlayerControllers->deletePlayer();
            break;
            case "player_to_season" :
                $seasonControllers->playerToSeason();
            break;
            case "season_manager" :
                $seasonControllers->seasonsManager();
            break;
            case "remove_player_of_season" :
                $seasonControllers->removePlayerOfSeason();
            break;
            case "player_manager" :
                $PlayerControllers->playerManager();
            break;
            case "create_article" :
                $articleControllers->createArticle();
            break;
            case "admin" :
                $adminControllers->admin();
            break;
            case "article_manager":
                $articleControllers->articleManager();
            break;
            case "update_article":
                $articleControllers->upDateArticle();
            break;
            case "delete_article":
                $articleControllers->deleteArticle();
            break;
            case "contact_us":
                $requestControllers->addRequest();
            break;
            case "deconnect":
                $adminControllers->deconnect();
            break;
            case "message_manager":
                $requestControllers->requestManager();
            break;
            case "message_details":
                $requestControllers->requestDetails();
            break;
            case "message_delete":
                $requestControllers->requestDelete();
            break;
            case "calendar":
                $gamesControllers->calendar();
            break;
            case "static_page":
                $pagesControlers->getPage();
            break;
            case "game_Ajax":
                $gamesControllers->gameAjax();
            break;
            case "create_game":
                $gamesControllers->createGame();
            break;
            case "game_manager":
                $gamesControllers->gamesManager();
            break;
            case "update_game":
                $gamesControllers->upDateGame();
            break;
            case "delete_game":
                $gamesControllers->deleteGame();
            break;
        }
    }else{
        $articleControllers->articlesForHome();
        
    }
