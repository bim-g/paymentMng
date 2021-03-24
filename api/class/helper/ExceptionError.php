<?php
class ExceptionError{
    static function message($msg=null){
        $message= "vous n'avez pas assez d'autorization pour effectuez cette operation";
        if($msg){
            $message= $msg;
        }
        return ["status" => 400, "message" => $message];
    }
}
?>