<?php
class View
{
    private $_viewFile;
    
    public function __construct($contr,$file)
    {
        $this->_viewFile = "application/views/".$contr."/".$file.".phtml";
    }
    
    public function render()
    {
        include($this->_viewFile);
    }
    
    public function append($propertyName,$propertyContent)
    {
        $this->$propertyName = $propertyContent;
    }
}
?>