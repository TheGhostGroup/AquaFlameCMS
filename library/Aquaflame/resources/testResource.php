<?php
class Test_Resource extends Resource_Abstract
{
    public function __construct($registry)
    {
        $this->_registry = $registry;
        $this->_name = "Test";
        parent::__construct();
    }
    
    public function WhatAFunction()
    {
        return "ghfgh";
    }
}
?>