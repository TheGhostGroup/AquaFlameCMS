<?php
class DB_Adapter
{
	protected $_Instance;
	
	public function getInstance()
	{
		return $this->_Instance;
	}
	
	public function setInstance($instance)
	{
		$this->_Instance = $instance;
		return $this;
	}
	
    
    public function setOption($option,$value)
    {
        
    }
}

?>