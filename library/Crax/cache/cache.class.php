<?php
class Cache
{
    private $_expiraryDate;
    
    public function __construct()
    {
        session_start();
        if(!isset($_COOKIE["cacheExpire"]))
        {
            setcookie("cacheExpire",md5("Crax".time()+60*60*12),time()+60*60*12);
        }elseif($_COOKIE["cacheExpire"] === md5("Crax".time())){
            session_destroy();
        }
    }
    
    public function __destruct()
    {
        session_commit();
    }
    
    public function put($name,$content)
    {
        $_SESSION[$name] = serialize($content);
    }
    
    public function check($name)
    {
        if(isset($_SESSION[$name]))
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function get($name)
    {
        if(isset($_SESSION[$name]))
        {
            $len = strlen($name);
            if (substr($name,$len-10,10) === 'Controller'){
                include_once 'library/Crax/controller/controller.class.php';
                include_once 'library/Crax/resources/resource.registry.php';
                include_once 'application/controllers/'.strtolower(substr($name,0,$len-10)).'.php';
            }else{
                switch($name)
                {
                    case 'Configuration':
                        include_once 'library/Crax/bootstrapper/configuration.class.php';
                        break;
                    case 'ResourceRegistry':
                        include_once 'library/Crax/resources/resource.abstract.php';
                        $result = scandir("library/Aquaflame/resources");
                        foreach($result as $file){
                            if(strstr($file,".php")){
                                include_once "library/Aquaflame/resources/".$file;             
                            }
                        }
                        break;
                }
            }
            return unserialize($_SESSION[$name]);
        }else{
            return FALSE;
        }
    }
    
    public function erase()
    {
        foreach($_SESSION as $element)
        {
            unset($element);
        }
    }

}
?>