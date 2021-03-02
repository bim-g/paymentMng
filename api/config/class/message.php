<?php

use Wepesi\App\Core\DB;
use Wepesi\App\appException;
use FFI\Exception;
use Wepesi\App\Pagination;
    class Message extends appException{
        private $bdd;

        function __construct()
        {
            $this->bdd=DB::getInstance();
        }
        function storemessage(array $fields){            
            try{
                $req= $this->bdd->insert("message")->fields($fields)->result();
                if($this->bdd->error()){
                    throw new exception("enable to send message");
                }                
                return $this->bdd->lastId();
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function getMessages($target){            
            try{
                $limit = 30;
                $pagination = (new Pagination)->getTotalRows("message", $limit, $target);
                // get start offet && number of pages
                $offset = $pagination->target;
                $totalpages = $pagination->pages;
                $req=$this->bdd->get("message")->limit((int)$limit)->offset((int)$offset)->result();
                $result = ["result" => (array)$req, "totalPages" => $totalpages, "activepages" => $target];
                return json_encode($result);
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function deleteMessage($id){
            try{
                $req= $this->bdd->delete("message")->where(["idmessage", "=", $id])->result();
                if(!$this->bdd->error()){
                    throw new exception("anable to delete message");
                }
                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }        
    }
?>