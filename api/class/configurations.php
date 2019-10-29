<?php
    class configurations{
        private $bdd;
        private $idServ;
        private $iddep;
        private $nameServ;
        function __construct($base,$id,$name)
        {
            $this->bdd=$base;
            $this->idServ=$id;
            $this->nameServ=$name;
        }

        function getServices($src){            
            $req=null;
            if($src!=null && (int)$src){
                $req.=" SELECT * FROM services JOIN departement ON services.iddepart=departement.iddepart WHERE iddepart=:idDep";
            }elseif($src!=null && $src="services"){
                $req=" SELECT * FROM services JOIN departement ON services.iddepart=departement.iddepart WHERE services.iddepart=departement.iddepart";
            }            
            try{
                $q=$this->bdd->prepare($req);  
                if($src!=null && (int)$src){
                    $q->bindParam(":idDep",$src);
                }                         
                $q->execute();
                $res=$q->fetchAll();
                echo json_encode($res);
            }catch(Exception $ex){
                echo "error_get departement=>".$ex->getMessage();
            } 
        }
        function getDepartment(){            
            $req="SELECT * FROM departement";            
            try{
                $q=$this->bdd->prepare($req);                            
                $q->execute();
                $res=$q->fetchAll();
                echo json_encode($res);
            }catch(Exception $ex){
                echo "error_get departement=>".$ex->getMessage();
            } 
        }
        function getGrade(){
            $req="SELECT idgrade,gradeName FROM grade";            
            try{
                $q=$this->bdd->prepare($req);                            
                $q->execute();
                $res=$q->fetchAll();
                echo json_encode($res);
            }catch(Exception $ex){
                echo "error_get departement=>".$ex->getMessage();
            } 
        }
        function getConfigSalary(){            
            $req="SELECT `idgrade`, `gradeName`, `netsalary`, `childprime` FROM grade";            
            try{
                $q=$this->bdd->prepare($req);                            
                $q->execute();
                $confSal=array();
                while($res=$q->fetch()){
                    array_push($confSal,array(
                        "idgrade"=>$res['idgrade'],
                        "gradeName"=>$res['gradeName'],
                        "netsalary"=>$res['netsalary'],
                        "primePost"=>$this->getPimePost($res['idgrade']),
                        "childprime"=>$res['childprime']
                    ));
                }
                echo json_encode($confSal);
            }catch(Exception $ex){
                echo "error_get departement=>".$ex->getMessage();
            } 
        }

        private function getPimePost($src){
            $req="SELECT typeprime,emp.mountprime FROM employeeprime emp JOIN primeongrade pri ON emp.idprime=pri.idprime WHERE pri.idgrade=:idgrade";            
            try{
                $q=$this->bdd->prepare($req); 
                $q->bindParam(":idgrade",$src);                         
                $q->execute();
                $prime=array();
                while($res=$q->fetch()){
                    array_push($prime,array(
                        "typeprime"=>$res['typeprime'],
                        "amountPrime"=>$res['mountprime']
                    ));
                }
                return $prime;
            }catch(Exception $ex){
                echo "error_getPrime of a post=>".$ex->getMessage();
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