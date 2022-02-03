<?php

// Statics Pages controllers
class PagesControllers
{

    public function getPage():void{

        if(array_key_exists('page',$_GET)){
            $page = $_GET['page'];
    
            switch($page){
                case 'quisommesnous':
                    $template = "www/pages/quisommesnous";
                break;
                case 'lesdeplacements' :
                    $template = "www/pages/lesdeplacements";
                break;
                case 'latribunei' :
                    $template = "www/pages/latribunei";
                break;
                case 'nousrejoindre' :
                    $template = "www/pages/nousrejoindre";
                break;
                case 'mentions' :
                    $template = "www/pages/mentions";
                break;
                case 'bdl' :
                    $template = "www/pages/bdl";
                break;
            }

            require "www/layout.phtml";
        }
    }
}


