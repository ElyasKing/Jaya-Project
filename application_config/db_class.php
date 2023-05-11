<?php 
require_once("db_config.php");

class Database{
    private static $db_dsn = DSN;
    private static $db_user = USER;
    private static $db_password = PWD;

    private static $connection = null;

    public static function connect(){
        try{
            self::$connection = new PDO(self::$db_dsn, self::$db_user, self::$db_password);
        }catch(Exception $e){
            echo $e->getMessage();
            echo $e->getCode();
            die();
        }
        return self::$connection;
    }

    public static function disconnect(){
        self::$connection = null;
    }
}
?>