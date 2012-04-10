<?php
abstract class Resource_Abstract
{
    protected $_name;
    protected $_registry;
    
    public function __construct()
    {
        $this->_RegisterSelf();
        $this->_loadDB();
    }
    
    public function __sleep()
    {
        return array("_name","_registry");
    }
    
    private function _RegisterSelf()
    {
        $this->_registry->_registerResource($this->_name,md5($this->_name),$this);
    }
    
    private function _loadDB()
    {
        include_once 'library/Crax/db/db_table.php';
        $result = scandir('application/models');
        foreach($result as $file)
        {
            if(is_file('application/models/'.$file))
            {
                include_once 'application/models/'.$file;
                if(!strpos($file,'_'))
                    include_once 'application/models/dbTable/'.$file;
            }
        }
    }
    
    protected function getDbStore()
    {
        
    }
}
?>