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
                $response=$this->user->connexion($data);
                if(!$response){
                    Response::send("echec de connexion :(",400);
                }else{
                    $token=Token::generate();
                    array_push($response,["token"=>$token]);
                    Response::send($response);
                }                
            } else {
                Response::send($this->validate->errors(), 400);
            }
        } else {
            Response::send("invalide data operation", 400);
        }
    }
    function register(){
        if(Input::exists()){
            $this->validate->check($_POST,[]);
        }else{
            Response::send("Operation echouez",400);
        }
    }
}
