<?php
require('library/Crax/cache/cache.class.php');
class Bootstrap
{
    private $_configuration;
    private $_dbStore;
    private $_cache;
    
    public function __construct()
    {
        $this->setCache(new Cache());
        if($this->getCache()->check('Configuration'))
        {
            $this->setConfiguration($this->getCache()->get('Configuration'));
        }else{
            require('library/Crax/bootstrapper/configuration.class.php');
            $this->setConfiguration(new Configuration());
            $this->getCache()->put('Configuration',$this->getConfiguration());
        }
        if($this->getCache()->check('DB_Store'))
        {
            $this->setDbStore($this->getCache()->get('DB_Store'));
        }else{
            require('library/Crax/db/db_store.php');
            $this->setDbStore(new DB_Store($this));
            $this->getCache()->put('DB_Store',$this->getDbStore());
        }
    }
    
    public function getDbStore()
    {
        return $this->_dbStore;
    }
    
    public function setDbStore($store)
    {
        $this->_dbStore = $store;
        return $this;
    }
    
    public function getConfiguration()
    {
        return $this->_configuration;
    }
    
    public function setConfiguration($config)
    {
        $this->_configuration = $config;
        return $this;
    }
    
    public function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }
    
    public function getCache()
    {
        return $this->_cache;
    }
    
    public function run()
    {
        include('library/Crax/controller/controller.factory.php');
        $controller = ControllerFactory::createController($this);
        $controller->run();
    }
}
?>