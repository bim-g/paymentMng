<?php

use Wepesi\App\Core\DB;
use Wepesi\App\appException;
use Wepesi\App\Pagination;

class Training extends appException{
        private $bdd;
        private $idtopic ;
        
        function __construct()
        {
            $this->bdd=DB::getInstance();
        }
        
        function addTopic($fields){            
            try{               
                if(!$this->bdd->insert("topic",$fields)){
                    throw new exception("unable to add new topic");
                }                
                return $this->bdd->lastId();
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function updatestateTopic($fields, $where){            
            try{
                if(!$this->bdd->update("topic",["idtopic"=>$where],$fields)){
                    throw new exception("Enable to update topic state");
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function updateTopic($fields, $where){ 
            try{                
                if(!$this->bdd->update("topic",["idtopic"=>$where],$fields)){
                    throw new exception("unable to update topic");
                };               
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function detailSolution($id){
            
            $sql= "SELECT s.idsolution,s.step,t.titletopic,s.description FROM `solution` s JOIN topic t ON s.idtopic=t.idtopic WHERE s.idsolution";
            $req=$this->bdd->query($sql,[$id])->result();
            try{
                $req->execute();
                return $req->fetchAll();
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function getTopics($id=null){
            $req=null;
            
            if($id!=null && is_integer($id)){                
                $sql= "SELECT t.idtopic,t.titletopic,t.intent,t.statetopic,t.summary,t.questions,t.dateregister,m.link as linkmedia FROM topic t LEFT JOIN media m ON t.imagerefernce=m.id WHERE idtopic=?";
                $req=$this->bdd->query($sql,[(int) $id])->result();
                
                $topics=array();            
                try{                    
                    $topics=$req;
                    $solutions=json_decode($this->getSolutions((int) $id),true);
                    return json_encode(
                            array(
                                "topic" => $topics,
                                "solutions" => $solutions
                            ));
                                
                }catch(Exception $ex){
                    return $this->exception($ex);
                }
            
            }else{
                $sql="SELECT t.idtopic,t.titletopic,t.intent,u.fname as username,t.statetopic,t.summary,t.questions,t.dateregister FROM topic t JOIN users u ON t.iduser=u.iduser ORDER BY t.idtopic DESC";
                try{
                    $req=$this->bdd->query($sql)->result();
                    return json_encode($req);
                }catch(Exception $ex){
                    return $this->exception($ex);
                }                
            }
            return false;
        }
        function getAllTopics(int $target){            
            try{
                $limit=10;
                // get pagination operator
                $pagination=(new Pagination)->getTotalRows("topic",$limit,$target);
                // get start offet && number of pages
                $offset=$pagination->target;
                $totalpages=$pagination->pages;
                // query select
                $sql="SELECT t.idtopic,t.titletopic,t.intent,u.fname as username,t.statetopic,t.summary FROM topic t JOIN users u ON t.iduser=u.iduser ORDER BY t.idtopic DESC LIMIT ? OFFSET ? ";
                // query execution
                $req=$this->bdd->query($sql,[(int)$limit,(int)$offset]);
                // 
                $result=["result"=>(array)$req->result(), "totalPages"=>$totalpages, "activepages"=>$target];
                return json_encode($result);
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }

        function getAllSolutions($target){
            try{
                // get pagination operator
                $sql = "SELECT s.idtopic,t.titletopic,s.idsolution,s.description,s.step,s.datelastupdate FROM solution s JOIN topic t ON s.idtopic=t.idtopic";
                $limit=30;
                $pagination = (new Pagination)->getTotalRows("solution", $limit, $target,$sql);
                
                // get start offet && number of pages
                $offset = $pagination->target;
                $totalpages = $pagination->pages;
                $sql.= " ORDER BY s.idtopic DESC LIMIT ? OFFSET ?";
                $req=$this->bdd->query($sql,[(int)$limit,(int)$offset])->result(); 
                $result=[
                    "result"=>(array)$req,
                    "totalPages"=>$totalpages,
                    "activepages"=>$target
                ];
                return json_encode($result);
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function getSolutions($id, $src = null){
           $req=null;
            $sql = "SELECT s.idtopic,t.titletopic,s.idsolution,s.description,s.step,s.datelastupdate FROM solution s JOIN topic t ON s.idtopic=t.idtopic";
           $idtopic=(int)$id;
            if($id!=null && is_integer($id)){ 
                $target= " WHERE s.idsolution=?";
                if($src==null){
                    $target= " WHERE s.idtopic=?";
                }
                $sql.= $target;
                $req=$this->bdd->query($sql,[(int)$id])->result();                
                try{                    
                    return json_encode($req);
                }catch(Exception $ex){
                    return $this->exception($ex);
                }
            }else{               
                try{
                    $req=$this->bdd->query($sql);                   
                    return json_encode($req->result());
                }catch(Exception $ex){
                    return $this->exception($ex);
                }
            }
            return false;
        }

        function removetopic($where){            
            try{
                if(!$this->bdd->delete("topic",$where)){
                    throw new exception("Enable to delete this topic");
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function removesolution($where){            
            try{
                if(!$this->bdd->delete("solution",$where)){
                    throw new exception("Enable to delete this topic");
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function addSolution($fields){            
            try{
                if(!$this->bdd->insert("solution",$fields)){
                    throw new exception("unable to add a topic solution");
                }
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function updateSolution($fields){
            $sql="UPDATE solution SET description=?,step=?,datelastupdate=NOW() WHERE idsolution=?";
            $req=$this->bdd->update("solution",$fields);
            
            try{
                $req->execute();
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function randomtopic(){
           $sql="SELECT t.titletopic,t.summary,m.link  FROM topic t JOIN media m ON t.imagerefernce=m.id ORDER BY RAND() LIMIT 2";
            $result=$this->bdd->query($sql)->result();          
            return json_encode($result);
        }
    }
?>