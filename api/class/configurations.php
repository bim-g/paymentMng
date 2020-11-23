<?php
    class configurations{
        private $bdd;
        function __construct()
        {
            $this->bdd=DB::getInstance();
        }

        function getServices($id=null){            
            $where=[];
            $table= "services JOIN departement ON services.iddepart=departement.iddepart";
            if((int)$id>0){
                $where=["iddepart","=",$id];                
            }           
            try{
                $res=$this->bdd->get($table,$where);  
                
                return json_encode($res->result());
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        function getDepartments($id=null){             
            try{
                $where=[];
                if((int)$id>0){
                    $where=["iddepart","=",$id];
                }
                $res=$this->bdd->get("departement",$where);
                return json_encode($res->result());
            }catch(Exception $ex){
                return "Exception=>".$ex->getMessage();
            } 
        }
        function getGrade(){          
            try{
                $res=$this->bdd->get("grade",[],["idgrade","gradeName"]);
                return json_encode($res->result());
            }catch(Exception $ex){
                return ["Exception"=>$ex->getMessage()];
            } 
        }
        function getConfigSalary(){                     
            try{
                $q=$this->bdd->get("grade",[],["idgrade","gradeName","netsalary","childprime"]);                            
                $res=json_encode($q->result());
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

        private function getPimePost($idgrade){            
            $table= "employeeprime emp JOIN primeongrade pri ON emp.idprime=pri.idprime";
            $where=["pri.idgrade","=",(int)$idgrade];
            try{
                $q=$this->bdd->get($table,$where,["typeprime", "emp.mountprime"]);                                      
                $res=json_encode($q->result());
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

        function addDepartement(){
            $req="INSERT INTO departement VALUES(0,:departName,CURRENT_TIMESTAMP)";            
            try{
                $q=$this->bdd->prepare($req); 
                $q->bindParam(":departName",$this->nameServ);                         
                $q->execute();
                echo "success depart";
            }catch(Exception $ex){
                echo "error_getPrime of a post=>".$ex->getMessage();
            }
        }
        function addServices(){
            $req="INSERT INTO services VALUES(0,:servName,:iddepart,CURRENT_TIMESTAMP)";            
            try{
                $q=$this->bdd->prepare($req); 
                $q->bindParam(":servName",$this->nameServ);                         
                $q->bindParam(":iddepart",$this->idServ);                         
                $q->execute();
                echo "success revices";
            }catch(Exception $ex){
                echo "error_getPrime of a post=>".$ex->getMessage();
            }
        }
        function deleteDepartment(){
            $req="DELETE FROM departement WHERE iddepart=:idDeprt"; 
            $serv=$this->deleteServices($this->idServ) ;
            if($serv){       
                try{
                    $q=$this->bdd->prepare($req); 
                    $q->bindParam(":idDeprt",$this->idServ);                         
                    $q->execute();
                    echo "delete_deport success";
                }catch(Exception $ex){
                    echo "error_getPrime of a post=>".$ex->getMessage();
                }
            }
        }
        function deleteServices($depart=false){
            $req="DELETE FROM services WHERE idservice=:idDeprt";           
            if($depart!=null){
                $req="DELETE FROM services WHERE iddepart=:idDeprt";  
            }
            try{
                $q=$this->bdd->prepare($req);
                if($depart!=null && (int)$depart){ 
                    $q->bindParam(":idDeprt",$depart);
                }else{
                    $q->bindParam(":idDeprt",$this->idServ);
                } 
                $q->execute();
                return true;
            }catch(Exception $ex){
                echo "error_getPrime of a post=>".$ex->getMessage();
            }
        }
    }
?>