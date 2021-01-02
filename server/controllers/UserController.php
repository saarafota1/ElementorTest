<?php
require __DIR__. '/../classes/JwtHandler.php';
require __DIR__. '/../classes/User.php';
require_once __DIR__ . '/../middlewares/Auth.php';

class UserController {
    private $params;
    public function __construct($params = []){
        $this->params = $params;
    }
    private function getUsersArray() {
        try{
            $users = file_get_contents('./data/users.json');
            $users = json_decode($users, true); 
            return $users;
        }
        catch(Exception $e){
            $returnData = Response::msg(0,500,$e->getMessage());
            Response::send($returnData);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        // CHECKING EMPTY FIELDS
        if(!isset($data["email"]) 
        || !isset($data["password"])
        || empty(trim($data["email"]))
        || empty(trim($data["password"]))
        ){

            $fields = ['fields' => ['email','password']];
            $returnData = Response::msg(0,422,'Please Fill in all Required Fields!',$fields);
            Response::send($returnData);
        // IF THERE ARE NO EMPTY FIELDS THEN-
        }else{
            $email = trim($data["email"]);
            $password = trim($data["password"]);

            // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $returnData =  Response::msg(0,422,'Invalid Email Address!');
                Response::send($returnData);
            }
            // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
            if(strlen($password) < 8){
                $returnData = Response::msg(0,422,'Your password must be at least 8 characters long!');
                Response::send($returnData);
            }

            try{
                $users = $this->getUsersArray();
                foreach($users as $user){
                    $user = new User($user["name"], $user["username"], $user["password"], $user["loginCount"], $user["lastUpdated"], $user["connected"], $user["registerTime"]);
                    if ($user->getUsername() == $email){
                        if ($user->getPassword() == $password){
                            $jwt = new JwtHandler();
                            $token = $jwt->_jwt_encode_data(
                                'http://localhost/php_auth_api/',
                                array("user_id"=> $user->getUsername())
                            );
                            
                            $returnData = [
                                'success' => 1,
                                'message' => 'You have successfully logged in.',
                                'token' => $token
                            ];
                            $user->setConnected(true);
                            $user->setLastUpdated(date('Y-m-d h:i:s'));
                            $user->addOneToLoginCount();
                            $user->setIP($this->getRemoteIP());
                            $user->setLastLogin(date('Y-m-d h:i:s'));
                            $user->setUserAgent($_SERVER['HTTP_USER_AGENT']);
                            $this->updateUserObject($user);
                            Response::send($returnData);
                            exit();
                        }
                    }
                }
                $returnData = Response::msg(0,422,'Invalid Email Address or Password!');
                Response::send($returnData);
            }catch(Exception $e){
                $returnData = Response::msg(0,500,$e->getMessage());
                Response::send($returnData);
            }
        }
    }

    public function fetchUser($username) {
        $users = $this->getUsersArray();
        foreach($users as $user) {
            if ($username == $user["username"]) {
                return $user;
            }
        }
        return null;
    }
    public function updateUser($userToUpdate){
        $users = $this->getUsersArray();
        foreach($users as $key => $user) {
            if ($user["username"] == $userToUpdate["username"]) {
                $users[$key] = $userToUpdate;
                $this->save($users);
                Response::send(Response::msg(1,204,'User Updated'));
                return;
            }
        }
        return null;
    }

    public function updateUserObject($userToUpdate) {
        $users = $this->getUsersArray();
        foreach($users as $key => $user) {
            if ($user["username"] == $userToUpdate->getUsername()) {
                $users[$key] = $userToUpdate->expose();
                $this->save($users);
                return;
            }
        }
        return null;
    }

    public function getUser(){
        Auth::checkAuth();
        $res = Response::msg(0, 404, 'User not found', isset($this->params)?$this->params:[]);
        if(isset($this->params)){
            if(count($this->params) > 0){
                $username = $this->params[0];
                $user = $this->fetchUser($username);
                if( $user !== null){
                    $res = Response::msg(1, 200, 'User found', ["user" => $user]);
                }
            }
        }
        Response::send($res);
    }
    
    public function fetchUsers(){
        Auth::checkAuth();
        
        $users = $this->getUsersArray();
        $returnData = Response::msg(1,200,'', ["data" => $users]);
        Response::send($returnData);
    }

    public function logout() {
        Auth::checkAuth();
        $data = json_decode(file_get_contents("php://input"), true);
        $username = isset($data["username"])?$data["username"]:"";

        $user = $this->fetchUser($username);
        $user["connected"] = false;
        $this->updateUser($user);
    }

    public function save($users){
        try{
            file_put_contents('./data/users.json',json_encode($users));
        }catch(Exception $e){
            Response::send(Response::msg(0,500,$e->getMessage()));
        }
        
    }

    public function getRemoteIP(){
        $ip_address = null;
        //whether ip is from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   
        {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
        {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else
        {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address == "::1"?"127.0.0.1":$ip_address;
    }
}