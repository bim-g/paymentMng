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
                $q=$this->bdd->get("grade")->field(["idgrade","gradeName","netsalary","childprime"])->result();                            
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
            $table= "employeeprime emp JOIN primeongrade pri ON emp.idprime=pri.idprime";
            $where=["pri.idgrade","=",(int)$idgrade];
            try{
                $q=$this->bdd->get($table)->where($where)->field(["typeprime", "emp.mountprime"])->result();                                      
                $res=json_encode($q);
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
            // $req="INSERT INTO departement VALUES(0,:departName,CURRENT_TIMESTAMP)";            
            try{
                $this->bdd->insert("departement")->field()->result();
                if ($this->bdd->error()) {
                    throw new Exception($this->bdd->error());
                }
                // $q=$this->bdd->prepare($req); 
                // $q->bindParam(":departName",$this->nameServ);                         
                // $q->execute();
                // echo "success depart";
                return true;
            }catch(Exception $ex){
                echo "error_getPrime of a post=>".$ex->getMessage();
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