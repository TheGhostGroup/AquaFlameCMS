<?php
class Layout
{
    private $content;
    private $_controller;
    
    public function __construct($controller)
    {
        $this->_controller = $controller;
    }
    
    public function _addView($view)
    {
        ob_start();
        $view->render();
        $this->content = ob_get_contents();
        ob_end_clean();
    }
    
    public function render()
    {
        include('application/layout.phtml');
    }
    
    public function placeholder($container,$content)
    {
        $this->$container = $content;
    }
}
?>