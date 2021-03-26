<?php
class userCtrl
{
    private $user;
    private $validate;
    function __construct()
    {
        $this->user = new users();
        $this->validate = new Validate();
    }
    function connexion()    {  
        if (Input::exists()) {
            $this->validate->check($_POST, [
                "username" => [
                    "required" => true,
                    "min" => 3,
                    "max" => 50
                ],
                "password" => [
                    "required" => true,
                    "min" => 3,
                    "max" => 50
                ],
            ]);
            if ($this->validate->passed()) {
                $username= Input::get('username');
                $password= md5(Input::get('password'));
                $data = [$username,$username, $password];                
                $result=$this->user->connexion($data);
                if(isset($result['error'])){
                    Response::send(["message"=> $result['error']],400);
                }else{    
                    $response=["status" => 400,"message" => "wrong login information"];
                    if($result){
                        $response=["status" => 200,"response" => $result,"message" => "connected"];
                    }         
                    Response::send($response);
                }                
            } else {
                Response::send($this->validate->errors(), 400);
            }
        } else {
            Response::send("invalide data operation", 400);
        }
    }
    
    private function getEmployee($id=null){
        return $this->user->getEmployee($id);
        
    }
    function getAllEmployee(){
        if (Token::check(Input::header('token'))) {
            $response=$this->getEmployee();
            Response::send(["status"=>200,"response"=>$response]);
        }else{
            Response::send(ExceptionError::message());
        }
    }
    function getEmployeDetail($id){
        // if(Token::check(Input::header('token'))){
            $response = $this->getEmployee($id);
            Response::send(["status"=>200,"response"=> $response]);
        // }else{
        //     Response::send(["status"=>400,"message"=>"vous n'avez pas assez d'autorization pour effectuez cette operation"]);
        // }
    }
    function register(){
        if(Input::exists()){
            $this->validate->check($_POST,[]);
        }else{
            Response::send("Operation echouez",400);
        }
    }
}
