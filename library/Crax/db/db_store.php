<?php
class DB_Store
{
    private $_configuration;
    private $_storage;
    private $_generalUIDList;
    private $_DbTableList;
    
    public function __construct($bootstrap)
    {
        $this->_configuration = $bootstrap->getConfiguration();
        $this->_startDB();
    }
    
    public function __sleep()
    {
        $this->_storage = array();
        return array('_configuration','_storage');
    }
    
    public function __wakeup()
    {
        $this->_startDB();
    }
    
    public function createNewAdapter($caller,$db)
    {
        $pdo = new PDO("mysql:host=".$this->_configuration->getOption("dbHost").";dbname=".$db, $this->_configuration->getOption("dbUser"), $this->_configuration->getOption("dbPass"));
        return $this->_registerAdapter($pdo,$db,$caller);
    }
    
    public function getAdapter($UID = 0, $general = true,$name = "RealmDB", $num = 1)
    {
        if($UID === 0 && $general){
            switch($name){
                case "RealmDB":
                    return $this->_storage[$this->_generalUIDList[0]][0];
                    break;
                case "WorldDB":
                    return $this->_storage[$this->_generalUIDList[1]][0];
                    break;
                default:
                    return $this->_storage[$this->_generalUIDList[1+$num]][0];
                    break;
            }
        }else{
            return $this->_storage[$UID][0];
        }
    }
    
    public function getDbNameByTable($table)
    {
        foreach($this->_DbTableList as $db => $tableName)
        {
            if($tableName === $table)
            {
                return $db;
            }
        }
    }
    
    public function checkUID($UID)
    {
        return isset($this->_storage[$UID]);
    }
    
    public function deleteAdapter($UID)
    {
        unset($this->_storage[$UID]);
    }
    
    private function _registerAdapter($PDO,$Name,$caller = "general")
    {
        do{
            $UID = md5($Name."_".$caller);
        }while($this->checkUID($UID));
        $this->_storage[$UID] = array($PDO,$Name,$caller);
        if($caller === "general"){
            $this->_generalUIDList[] = $UID;
        }
        $this->_fillDbTables($PDO,$Name);
        return $UID;
    }
    
    private function _startDB()
    {   
        for($i = 0; $i < $this->_configuration->getCountCharsDB() + 2;$i++)
        {
            if($i == 0){
                $this->_registerAdapter(new PDO("mysql:host=".$this->_configuration->getOption("dbHost").";dbname=".$this->_configuration->getDBName("World"), $this->_configuration->getOption("dbUser"), $this->_configuration->getOption("dbPass")),"WorldDB"); 
            }elseif($i == 1){
                $this->_registerAdapter(new PDO("mysql:host=".$this->_configuration->getOption("dbHost").";dbname=".$this->_configuration->getDBName("Realm"), $this->_configuration->getOption("dbUser"), $this->_configuration->getOption("dbPass")),"RealmDB");
            }elseif($i == 2){
                $this->_registerAdapter(new PDO("mysql:host=".$this->_configuration->getOption("dbHost").";dbname=".$this->_configuration->getDBName("Character"), $this->_configuration->getOption("dbUser"), $this->_configuration->getOption("dbPass")),"Character".$i-1);
                unset($tmp,$charDB);
            }
        }
    }
    
    private function _fillDbTables($PDO,$DB)
    {
        $tables = $PDO->query("SHOW TABLES")->fetchAll();
        foreach($tables as $table)
        {
            $this->_DbTableList[$DB] = $table;
        }
    }
}
?>