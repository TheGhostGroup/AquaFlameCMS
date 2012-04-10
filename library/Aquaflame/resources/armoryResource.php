<?php
class Armory_Resource extends Resource_Abstract
{
	static public function createCharacter($charName)
	{
        include_once 'library/Aquaflame/resources/armory/armory.abstract.php';
        include_once 'library/Aquaflame/resources/armory/armory.character.php';
		$character =  new Armory_Character($charName);
        $character->setCharDb($this->_registry->getBootstrap()->getDbStore()->getAdapter($this->_registry->getBootstrap()->getDbStore()->createNewAdapter('armory',$this->_registry->getBootstrap()->getConfiguration()->getDbName('Characrter',0))));
        $character->setWorldDb($this->_registry->getBootstrap()->getDbStore()->getAdapter($this->_registry->getBootstrap()->getDbStore()->createNewAdapter('armory',$this->_registry->getBootstrap()->getConfiguration()->getDbName('World'))));
        $character->_prerun();
        return $character;
	}
}
?>