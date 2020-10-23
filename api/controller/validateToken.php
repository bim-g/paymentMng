<?php

    class validateToken{
        function check(){
            if (!Token::check(Input::header('token'))) {           
                Response::send(["status" => 400, "message" => "vous n'avez pas assez d'autorization pour effectuez cette operation"]);
            }
            return true;
        }
    }