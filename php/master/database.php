<?php
class DataBase{
    var $pdoInstance = null;
    function __construct($host,$dbName,$usr,$pass){
        $this->pdoInstance = new PDO("mysql:host=$host;dbname=$dbName",$usr,$pass);
        $this->pdoInstance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    function QuerySQL($query,$fetchMode = PDO::FETCH_BOTH){
        return $this->pdoInstance->query($query)->fetchAll($fetchMode);
    }
    function ExecSQL($exec){
        return $this->pdoInstance->exec($exec);
    }
    function TableExists($tableName){
        $tList = $this->QuerySQL("SHOW TABLES LIKE '$tableName'");
        if(count($tList) > 0) return true;
        else return false;
    }
    function quote($content){
        return $this->pdoInstance->quote($content);
    }
}
function GetDatabase(){
    $dbConfig = ReadConfigProfile("mysql");
    $docRoot = $_SERVER["DOCUMENT_ROOT"].'/';
    $db = new DataBase($dbConfig['host'],$dbConfig['dbName'],$dbConfig['user'],$dbConfig['pass']);
    return $db;
}
?>