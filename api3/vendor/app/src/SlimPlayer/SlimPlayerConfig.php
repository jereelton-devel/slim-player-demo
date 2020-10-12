<?php

namespace SlimPlayer;

class SlimPlayerConfig
{   
    private $hostanme;
    private $username;
    private $password;
    private $dbname;
    private $server;
    
    public function getHostName()
    {
        return $this->hostanme;
    }
    
    public function getUserName()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getDbName()
    {
        return $this->dbname;
    }
    
    public function getServerName()
    {
        return $this->server;
    }

    public function __construct()
    {
        $this->hostanme = "localhost";
        $this->username = "devel";
        $this->password = "123mudar";
        $this->dbname   = "slim_player";
        $this->server   = $this->hostanme;
        
    }
	
}
?>