<?php namespace Waka\Publisher\Classes\Compiler;


Class Exemple {

    public static $elem;


    public static function prepareVars() {
        self::$elem = "Exemple";
    }

    /**
     * 
     */
    public static function proceed($contents) {
        self::prepareVars();
        $result = self::$elem;
        return $result;
    }
}