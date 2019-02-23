<?php
class GoogleTranslateHelper {
    static private $instance = null;

    public static function getInstance($srcLanguage,$destLanguage){
        if(self::$instance==null){
            require_once('vendor/autoload.php');
            self::$instance = new Stichoza\GoogleTranslate\TranslateClient();
        }

        self::$instance->setSource($srcLanguage);
        self::$instance->setTarget($destLanguage);
        return self::$instance;
    }

    public static function translate($message,$srcLanguage,$destLanguage){
        return self::getInstance($srcLanguage,$destLanguage)->translate($message);
    }
}