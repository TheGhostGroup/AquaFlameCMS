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
	
	public function makeArgumentsSafe($arguments)
	{
		if(is_array($arguments))
		{
			$result = array();
			foreach($arguments as $argument)
			{
				$result[] = $this->getInstance()->quote($argument); 
			}
		}else{
			$result = $this->getInstance()->quote($arguments);
		}
		return $result;
	}
	
	public function prepareStatement($sql,$arguments)
	{
		$stmn = $this->getInstance()->prepare($sql);
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
	
	public function runQuery($sql)
	{
		try{
			$this->getInstance()->beginTransaction()
								->exec($sql)
								->commit();
		}
		catch(PDOException $error)
		{
			$this->getInstance()->rollback();
			return $error->getCode();
		}
		
	}
	
	public function fetchResult($result)
	{
	   if(1 < $result->rowCount())
	   {
	       return $result->fetchAll();
	   }else{
	       return $result->fetch(PDO::FETCH_LAZY);			
	   }
	}
	
}

?>