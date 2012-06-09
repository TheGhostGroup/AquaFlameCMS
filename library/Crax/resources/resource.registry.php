<?php
class Resource_Registry
{
    private $_resourceRegister = array();
    private $_bootstrap;
    
    public function __construct($bootstrap)
    {
        $this->_bootstrap = $bootstrap;
        $this->_scanResources();
    }
    
    public function __sleep()
    {
        return array('_resourceRegister','_bootstrap');
    }
    
    public function __wakeup()
    {
        include_once 'library/Crax/resources/resource.abstract.php';
    }
    
    public function getBootstrap()
    {
        return $this->_bootstrap;
    }
    
    public function getResource($Name)
    {
        foreach($this->_resourceRegister as $resource){
            if($resource[0] === $Name)
                return $resource[1];
            else   
                return false;
        }
    }
    
    public function _registerResource($name,$UID,$obj)
    {
        if(!$this->_checkResourceExists($UID)){
            $this->_resourceRegister[$UID] = array($name,$obj);
        }else{
            return FALSE;
        }
    }
    
    private function _scanResources()
    {
        include_once "library/Crax/resources/resource.abstract.php";
        $result = scandir("library/Aquaflame/resources");
        foreach($result as $file){
            if(strstr($file,".php")){
                include_once "library/Aquaflame/resources/".$file;
                $name = ucfirst(substr($file,0,strlen($file)-12))."_Resource";
                new $name($this);
            }
        }
    }
    
    private function _checkResourceExists($UID)
    {
        return isset($this->_resourceRegister[$UID]);
    }
}
?>