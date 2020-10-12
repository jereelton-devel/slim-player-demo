<?php

namespace SlimPlayer;

class SlimPlayerConnection extends \PDO
{   
    private $config;
    private $connection;
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function __construct()
    {
        $this->config = new SlimPlayerConfig();
        
        $this->connection = new \PDO(
                "mysql:"
                . "host=".$this->config->getHostName().";"
                . "dbname=".$this->config->getDbName(), 
                $this->config->getUserName(), 
                $this->config->getPassword());
        
    }

}
?>