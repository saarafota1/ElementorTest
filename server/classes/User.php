<?php 

class User {
    protected $name;
    protected $username;
    protected $password;
    protected $loginCount;
    protected $lastUpdated;
    protected $connected;

    public function __construct($name, $username, $password, $loginCount=0, $lastUpdated = null, $connected = false,$registerTime = null, $userIP = null, $lastLogin = null, $userAgent = null)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->loginCount = $loginCount;
        $this->lastUpdated = $lastUpdated !== null?$lastUpdated:date("Y/m/d h:i:s");
        $this->connected = $connected;
        $this->userIP = $userIP;
        $this->lastLogin = $lastLogin;
        $this->userAgent = $userAgent;
        $this->registerTime = $registerTime;
    }

    public function getName(){
        return $this->name;
    }
    public function getUsername(){
        return $this->username;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getLoginCount(){
        return $this->loginCount;
    }
    public function getLastUpdated(){
        return $this->lastUpdated;
    }
    public function getConnected(){
        return $this->connected;
    }
    public function setConnected($val){
        $this->connected = $val;
    }
    public function setLastUpdated($val){
        $this->lastUpdated = $val;
    }
    public function setIP($val){
        $this->userIP = $val;
    }
    public function setLastLogin($val){
        $this->lastLogin = $val;
    }
    public function setUserAgent($val){
        $this->userAgent = $val;
    }
    public function addOneToLoginCount(){
        $this->loginCount++;
    }
    public function expose() {
        return get_object_vars($this);
    }
}