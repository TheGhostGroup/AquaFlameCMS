<?php
class HTTP_Request
{
    private $_requestedURI;
    private $_controller;
    private $_action;
    private $_parameters;
    private $_isValid;
    
    public function __construct($URI,$relPath)
    {
        $this->_requestedURI = $URI;
        $this->_segment($relPath);
        $this->_validateRequest();
    }
    
    public function __sleep()
    {
        return array("_requestedURI","_controller","_action","_parameters","_isValid");
    }
    
    private function _segment($relPath)
    {
        $elements = explode("/",$this->_requestedURI);
        array_shift($elements);
        if($relPath != "/")
            array_shift($elements);
        if(empty($elements[0])){
            $this->_controller = "index";
            $this->_action = "index";
        }else{
            foreach($elements as $element){
                $element = $this->escapeString($element);
            }
            $this->_controller = $element[0];
            $this->_action = $elements[1];
            $i= 2;
            for(;$i < count($elements);$i++)
            {
                $this->_parameters[] = $elements[$i];
            }
        }
    }
    
    private function _validateRequest()
    {
        if(file_exists("application/controllers/".$this->_controller.".php"))
        {
            $this->_isValid = TRUE;
        }else{
            $this->_isValid = FALSE;
        }
    }
    
    public function escapeString($str) 
    {
        if ($str !== null) {
		  $str = str_replace(array('\\','\''),array('\\\\','\\\''),$str);
		  $str = "'".$str."'";
        } else {
		  $str = "null";
        }
        return $str;
    }
    
    public function getModule()
    {
        return $this->_module;
    }
    
    public function getController()
    {
        return $this->_controller;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    public function getParameter($num)
    {
        if($num < count($this->_parameters))
        {
            return $this->_parameters[$num];
        }else{
            return FALSE;
        }
    }
    
    public function getParameters()
    {
        return $this->_parameters;
    }
    
    public function isValid()
    {
        return $this->_isValid;
    }
}
?>