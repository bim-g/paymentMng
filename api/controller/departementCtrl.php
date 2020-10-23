<?php
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
                Response::send($result);
            }else{                
                Response::send(["status" => 400, "message" => "vous n'avez pas assez d'autorization pour effectuez cette operation"]);
            }
        }
        function getServices(){
            if(Token::check(Input::header('token'))){
                $result=$this->departement->getServices();
                Response::send($result);
            }else{                
                Response::send(["status" => 400, "message" => "vous n'avez pas assez d'autorization pour effectuez cette operation"]);
            }
        }

        function getGrades(){
            if (Token::check(Input::header('token'))) {
                $result = $this->departement->getGrade();
                Response::send($result);
            } else {
                Response::send(["status" => 400, "message" => "vous n'avez pas assez d'autorization pour effectuez cette operation"]);
            }
        } 
        function getSalary(){
            if (Token::check(Input::header('token'))) {
                $result = $this->departement->getConfigSalary();
                Response::send($result);
            } else {
                Response::send(["status" => 400, "message" => "vous n'avez pas assez d'autorization pour effectuez cette operation"]);
            }
        } 

    }