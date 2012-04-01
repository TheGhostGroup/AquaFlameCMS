<?php
abstract class DB_DbTable
{
    protected $_dbStore;
    protected $_adapter;
    protected $_name;
    protected $_activeQuery;
    
    public function __construct()
    {
        $cache = new Cache();
        if($cache->check('DB_Store'))
        {
            $this->_dbStore = $cache->get('DB_Store');
        }
        $this->_adapter = $this->_dbStore->getAdapter(0,true,$this->_dbStore->getDbNameByTable($this->_name));
        include_once "library/Crax/db/db_rowset.php";
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
    
    public function fetch($stmn)
    {
        return $this->_fetch($stmn);
    }
    
    public function fetchAll($stmn)
    {
        return $this->_fetch($stmn);
    }
    
    public function find($stmn,$field,$value)
    {
        $result = $this->_fetch($stmn);
        $rowset = new DB_Rowset();
        foreach($result as $row)
        {
            if($row->$field == $value)
            {
                $rowset->add($row);
            }
        }
        return $rowset;
    }
    
    public function insert($fields)
    {
        $this->_activeQuery = "INSERT INTO `".$this->_name."` (";
        foreach($fields as $field)
        {
            $this->_activeQuery .= "`".$field."`";
        }
        $this->_activeQuery .= ") VALUES ";
    }
    
    public function values($values)
    {
        $saveValues = $this->_makeArgumentsSafe($values);
        $this->_activeQuery .= "(";
        foreach($saveValues as $value)
        {
            $this->_activeQuery .= "`".$value."`";
        }
        $this->_activeQuery .= ")";
    }
    
    public function where($field,$values,$type)
    {
        $this->_activeQuery .= " WHERE ".$field." ".$type." ";
        if(count($values) > 1 && is_array($values))
            $this->values($values);
        elseif(!is_array($values))
            $this->_activeQuery .= $values;
    }
    
    public function update($fields,$values)
    {
        $safeValues = $this->_makeArgumentsSafe($values);
        $this->_activeQuery .= "UPDATE ".$this->_name." SET ";
        for($i = 0; $i < count($fields);$i++)
        {
            $this->_activeQuery .= $fields[$i]." = ".$values[$i]." ";
        }
    }
    
    public function delete()
    {
        $this->_activeQuery .= "DELETE FROM ".$this->_name;
    }
    
    public function select($fields = null)
    {
        $this->_activeQuery .= "SELECT ";
        if($fields == null){
            $this->_activeQuery .= "* ";
        }else{
            for($i = 0; $i < count($fields);$i++){
                if($i == count($fields) - 2){
                    $this->_activeQuery .= $fields[$i];
                }else{
                    $this->_activeQuery .= $fields[$i].",";
                }
            }
        }
        $this->_activeQuery .= " FROM ".$this->_name;
    }
    
    public function getActiveQuery()
    {
        return $this->_activeQuery;
        $this->_activeQuery = '';
    }
	
	private function _fetch($stmn,$criteria = null)
	{
	   if(1 < $result->rowCount())
	   {
	       return new DB_Rowset($stmn->fetchAll(PDO::FETCH_LAZY));
	   }else{
	       return $stmn->fetch(PDO::FETCH_LAZY);			
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