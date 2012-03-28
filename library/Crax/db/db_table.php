<?php
abstract class Db_table
{
    protected $_dbStore;
    protected $_adapter;
    protected $_name;
    
    public function __construct()
    {
        $cache = new Cache();
        if($cache->check('DB_Store'))
        {
            $this->_dbStore = $cache->get('DB_Store');
        }
        $this->_adapter = $this->_dbStore->getAdapter(0,true,$this->_dbStore->getDbNameByTable($this->_name));
    }
	
	public function prepareStatement($sql,$arguments)
	{
		$stmn = $this->_adapter->prepare($sql);
		if(is_array($arguments))
		{			
			for($i = 0; $i < count($arguments);$i++)
			{
				$stmn->bindValue($i+1,$arguments[$i]);
			}
		}else{
			$stmn->bindValue(1,$arguments);
		}
		return $stmn;
	}
	
	public function query($sql)
	{
		try{
			$this->_adapter->beginTransaction()
								->exec(PDO::quote($sql))
								->commit();
		}
		catch(PDOException $error)
		{
			$this->_adapter->rollback();
			return $error->getCode();
		}
		
	}
    
    public function run($sql,$arguments)
    {
        $stmn = $this->prepareStatement($sql,$this->_makeArgumentsSafe($arguments));
        return $stmn->execute();
    }
	
	private function _fetch($result,$criteria = null)
	{
	   if(1 < $result->rowCount())
	   {
	       return $result->fetchAll();
	   }else{
	       return $result->fetch(PDO::FETCH_LAZY);			
	   }
	}
    
    private function _makeArgumentsSafe($arguments)
	{
		if(is_array($arguments))
		{
			$result = array();
			foreach($arguments as $argument)
			{
				$result[] = $this->_adapter->quote($argument); 
			}
		}else{
			$result = $this->_adapter->quote($arguments);
		}
		return $result;
	}
}
?>