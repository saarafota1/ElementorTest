<?php
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__.'/../classes/JwtHandler.php';
class Auth extends JwtHandler{

    protected $headers;
    protected $token;
    public function __construct($headers) {
        parent::__construct();
        $this->headers = $headers;
    }

    public function isAuth(){
        if(array_key_exists('authorization',$this->headers) && !empty(trim($this->headers['authorization']))):
            $this->token = explode(" ", trim($this->headers['authorization']));
            if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                $data = $this->_jwt_decode_data($this->token[1]);
                if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                    $userController = new UserController();
                    $user = $userController->fetchUser($data['data']->user_id);
                    return $user;

                else:
                    return null;

                endif; 
                
            else:
                return null;

            endif;

        else:
            return null;

        endif;
    }


    public static function checkAuth(){
        $allHeaders = getallheaders();
        $auth = new Auth($allHeaders);
        $returnData = [
            "success" => 0,
            "status" => 401,
            "message" => "Unauthorized"
        ];

        if($auth->isAuth() === null){
            Response::send($returnData);
        }

        return;
    }
}