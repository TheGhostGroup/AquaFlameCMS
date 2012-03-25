<?php
class Configuration
{
    private $_dbHost = "localhost";
    private $_dbUser = "root";
    private $_dbPass = "kabeli";
    
    private $_dbName = array("Character" => array("characters"),"World" => "world","Realm" => "realm");
    
    //private $_modulesEnabled = FALSE;
    
    public function getOption($name)
    {
        $var = "_".$name;
        return $this->$var;
    }
    
    public function getDBName($db)
    {
        return $this->_dbName[$db];
    }
    
    public function getCountCharsDB()
    {
        return count($this->_dbName["Character"]);
    }
}
?>