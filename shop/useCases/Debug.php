<?php
namespace shop\useCases;


class Debug{

    public static function debug($array, $die = true){
        if ($die){
            echo '<pre>';
            print_r($array);
            echo '</pre>';
            die();
        } else {
            echo '<pre>';
            print_r($array);
            echo '</pre>';
        }
    }

}