<?php
abstract class Controller
{
    protected $view;
    protected $request;
    protected $layout;
    private $_hasLayout = FALSE;
    
    public function __construct($request)
    {
        $this->request = $request;
        $this->preAction();
    }
    
    public function __sleep()
    {
        
        return array("view","request","layout");
    }
    
    public function __wakeup()
    {
        
    }
    
    private function _loadDB()
    {
        $result = scandir("application/models");
        foreach($result as $file)
        {
            if(is_file("application/models/".$file))
            {
                include_once "application/models/".$file;
            }
        }
    }
    
    public function postAction()
    {
        ob_get_flush();
    }
    
    public function preAction()
    {
        include_once('library/Crax/view/view.factory.php');
        if(file_exists('application/layout.phtml'))
        {
            $this->_hasLayout = TRUE;
            $this->layout = ViewFactory::createLayout($this->getRequest()->getController());
            $this->view = ViewFactory::createView($this->getRequest()->getController(),$this->getRequest()->getAction());
        }else{
            $this->view = ViewFactory::createView($this->getRequest()->getController(),$this->getRequest()->getAction());
        }
        ob_start();
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function setRequest($req)
    {
        $this->request = $req;
        return $this;
    }
    
    public function run()
    {
        $this->_loadDB();
        $action = $this->getRequest()->getAction()."Action";
        try{
            $this->preAction();
            $this->$action();
            if($this->_hasLayout)
            {
                $this->layout->_addView($this->view);
                $this->layout->render();
            }else{
                $this->view->render();
            }
            $this->postAction();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        //var_dump($this);
    }
}
?>