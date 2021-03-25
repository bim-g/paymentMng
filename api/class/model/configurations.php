<?php
    class configurations{
        private $bdd;
        function __construct()
        {
            $this->bdd=DB::getInstance();
        }

        function getServices(int $id=null){            
            $where=[];
            $table= "services JOIN departement ON services.iddepart=departement.iddepart";
            if((int)$id>0){
                $where=["iddepart","=",$id];                
            }           
            try{
                $res=$this->bdd->get($table)->where($where)->result();
                return json_encode($res);
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        function getDepartments(int $id=null){             
            try{
                $where=[];
                if((int)$id>0){
                    $where=["iddepart","=",$id];
                }
                $res=$this->bdd->get("departement")->where($where)->result();
                return json_encode($res);
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        function getGrade(){          
            try{
                $res=$this->bdd->get("grade")->field(["idgrade","gradeName"])->result();
                return json_encode($res);
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        // 
        function getConfigSalary(){                     
            try{
                $q=$this->bdd->get("grade")->fields(["idgrade","gradeName","netsalary","childprime"])->result();                            
                $res=json_encode($q);
                $res=json_decode($res,true);
                $confSalary=[];
                foreach($res as $item){
                    array_push($confSalary,array(
                        "idgrade"=>$item['idgrade'],
                        "gradeName"=>$item['gradeName'],
                        "netsalary"=>$item['netsalary'],
                        "primePost"=>$this->getPimePost($item['idgrade']),
                        "childprime"=>$item['childprime']
                    ));
                }
                return json_encode($confSalary);
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        // 
        private function getPimePost(int $idgrade){            
            $table= "employeeprime as em_p JOIN primeongrade as pri_on ON em_p.idprime=pri_on.idprime";
            $where=["pri_on.idgrade","=",(int)$idgrade];
            try{
                
                    $query = $this->bdd->query("select em_p.typeprime,em_p.mountprime FROM employeeprime em_p JOIN primeongrade pri_on ON em_p.idprime=pri_on.idprime WHERE pri_on.idgrade=?",[(int)$idgrade])->result();                                    
                    if($this->bdd->error()){
                        throw new Exception($this->bdd->error());
                    }; 
                    $res=json_encode($query);
                    $result=json_decode($res,true);             
                    $prime=array();
                    foreach($result as $item){
                        array_push($prime,array(
                            "typeprime"=>$item['typeprime'],
                            "amountPrime"=>$item['mountprime']
                        ));
                    }
                    return $prime;
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            }
        }
        // 
        function addDepartement(){      
            try{
                $this->bdd->insert("departement")->field()->result();
                if ($this->bdd->error()) {
                    throw new Exception($this->bdd->error());
                }
                return true;
            }catch(Exception $ex){
                return[ "error".$ex->getMessage()];
            }
        }
        // 
        function addServices(){
            // $req="INSERT INTO services VALUES(0,:servName,:iddepart,CURRENT_TIMESTAMP)";            
            try{
                $this->bdd->insert("services")->field()->result();
                if($this->bdd->error()){
                    throw new Exception($this->bdd->error());
                }
                // $q=$this->bdd->prepare($req); 
                // $q->bindParam(":servName",$this->nameServ);                         
                // $q->bindParam(":iddepart",$this->idServ);                         
                // $q->execute();
                return true;
            }catch(Exception $ex){
                return ["error_getPrime of a post=>".$ex->getMessage()];
            }
        }
        // 
        function deleteDepartment(){
            $serv=$this->deleteServices($this->idServ) ;
            try{
                if($serv){       
                    $this->bdd->delete("departement")->where(["iddepart","=", $this->idServ])->result();
                    if($this->bdd->error()){
                        throw new Exception($this->bdd->error());
                    }
                }
            }catch(Exception $ex){
                return ["error_getPrime of a post=>".$ex->getMessage()];
            }
        }
        // 
        function deleteServices(int $depart=null){
            try{
                if($depart!=null && (int)$depart){ 
                    $this->dbb->delete("services")->where(["iddepart", "=", $depart])->result();
                    if($this->bdd->error()){
                        throw new Exception($this->bdd->error());
                    }
                    return true;
                }else{
                    $this->dbb->delete("services")->where(["idservice","=", $depart])->result();
                    if ($this->bdd->error()) {
                        throw new Exception($this->bdd->error());
                    }
                    return true;
                }
            }catch(Exception $ex){
                return ["error_getPrime of a post=>".$ex->getMessage()];
            }
        }
    }
?>