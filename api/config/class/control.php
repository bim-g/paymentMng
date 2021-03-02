<?php

use Wepesi\App\Core\{DB,Media};
use Wepesi\App\appException;

    class Control extends appException{
        private $bdd;
        function __construct()
        {
            $this->bdd=DB::getInstance();
        }

        function getDomain($id=false){
            if(!$id){
                try{
                    $req=$this->bdd->get("usersdomaine")->result();                    
                    return json_encode($req);
                }catch(Exception $ex){
                    return $this->exception($ex);
                }
            }
        }

        function getlevels(){
            try{                
                $req=$this->bdd->get("userlevel")->where(["designation","<>","facebook"])->fields(["id", "designation"])->result();
                return json_encode($req);
            }catch(Exception $ex){
                return $this->exception($ex); 
            }
        }

        function addDomaine(){
            $sql="";
        }
        
        function insert_media($file, $fields){
            $media= New Media("media");
            $result= json_decode($media->uploadImg($file),true);
            $userid=$fields['userid'];
            $topicid=$fields['topicid'];
            $data=[
                    "name"=>$result["name"], 
                    "extension"=>$result["extension"], 
                    "link"=>$result["link"],  
                    "dateupload"=>date('Y-m-d H:i:s')
                ];
            try{
                $data= array_merge($data,["iduser" => $fields['userid']]);
                $this->bdd->insert("media")->fields($data)->result();
                $lastid = $this->bdd->lastid();
                if($this->db->error()){
                    throw new Exception("impossible d'enregister cette image");
                }
                if($topicid){
                    $this->bdd->update("topic")->fields(["idtopic" => $topicid])->where(["imagerefernce" => $lastid])->result();
                    if ($this->db->error()) {
                        throw new exception("imposible de mettre a jour l'image du topic");
                    }
                        return true;
                                                        
                }else{
                    $this->bdd->update("users")->fields(["iduser" => $userid])->where(["idavatar" => $lastid])->result() ;              
                    if($this->db->error()){
                        throw new exception("unable to add this image");
                    }
                    return true;                    
                }                
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }   
        
    }
?>