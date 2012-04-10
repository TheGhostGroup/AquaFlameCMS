<?php 
abstract class Armory
{
	protected $_objectId;
	protected $_objectInfo;
	protected $_statInfo;
	protected $_talentInfo;
	protected $_charDb;
	protected $_worldDb;
	abstract function run();
	
	public function __construct($id)
	{
		$this->_objectId = $id;
	}
	
	public function getObjectId()
	{
		return $this->_objectId;
	}
	
	public function setObjectId($id)
	{
		$this->_objectId = (int) $id;
		return $this;
	}
	
	public function getObjectInfo()
	{
		return $this->_objectInfo;
	}
	
	public function setObjectInfo($info)
	{
		$this->_objectInfo = $info;
		return $this;
	}
	
	public function getStatInfo()
	{
		return $this->_statInfo;
	}
	
	public function setStatInfo($info)
	{
		$this->_statInfo = $info;
		return $this;
	}

  	public function getTalentInfo()
	{
		return $this->_talentInfo;
	}
	
	public function setTalentInfo($info)
	{
		$this->_talentInfo = $info;
		return $this;
	}
	
	public function getCharDb()
	{
		return $this->_charDb;
	}
	
	public function setCharDb($pdo)
	{
		$this->_charDb = $pdo;
		return $this;
	}
	
	public function getWorldDb()
	{
		return $this->_worldDb;
	}
	
	public function setWorldDb($pdo)
	{
		$this->_worldDb = $pdo;
		return $this;
	}
	
}
?>