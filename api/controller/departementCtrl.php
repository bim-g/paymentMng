<?php
    // Controller::useController("ExceptionError");
    class departementCtrl{
        private $validate;
        private $departement;

        function __construct()
        {
            $this->validate=new Validate();
            $this->departement=new configurations();
        }
        private function getDepartement($id=null){
            $result=$this->departement->getDepartments($id);
            
        }
        function getAllDepartements(){
            if(Token::check(Input::header('token'))){
                $result=$this->departement->getDepartments();
                Response::send(["status"=>200,"response"=>$result]);
            }else{                
                Response::send(ExceptionError::message());
            }
        }
        function getServices(){
            if(Token::check(Input::header('token'))){
                $result=$this->departement->getServices();
                Response::send(["status" => 200, "response" => $result]);
            }else{                
                Response::send(ExceptionError::message());
            }
        }

        function getGrades(){
            if (Token::check(Input::header('token'))) {
                $result = $this->departement->getGrade();
                Response::send($result);
            } else {
                Response::send(ExceptionError::message());
            }
        } 
        
        function getSalary(){
            if (Token::check(Input::header('token'))) {
                $result = $this->departement->getConfigSalary();
                Response::send(["status" => 200, "response" => $result]);
            } else {
                Response::send(ExceptionError::message());
            }
        } 

        // 
        static function addDepartement(){
        }

    }