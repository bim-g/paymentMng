<?php
    class users{
        
        private $bdd;

        function __construct()
        {
            $this->bdd=DB::getInstance();            
        }
       
        function connexion(array $data=[]){
            $sql="SELECT * FROM users us JOIN employee em ON us.idemployee=em.idemployee  WHERE (em.email=? OR us.username=?) AND us.passwd=?";
            try{
                $res=$this->bdd->query($sql,$data);  
                if($res->result())  {
                    $result=json_encode($res->result());   
                    $result=json_decode($result);             
                    array_push($result, ["token" => Token::generate()]);
                    return json_encode($result);
                }            
                 return false;
            }catch(Exception $ex){
                 return ["Exception"=>$ex->getMessage()];
            }            
        }
        function getEmployee(int $userid=null,$marital=null,int $depart=null,int $serv=null,int $child=null){
            $iddepart=$depart;
            $iddservice=$serv;
            // $iddchild=$this->getNumChild($child);
            $req="SELECT emp.idemployee, emp.Fname, emp.Lname, emp.birthday, emp.sexe, emp.email, emp.phone, emp.maretalStatus, emp.datecreate FROM employee emp ";            
            // if($child!=null){
            //     $req.=" JOIN empDependancy empd on emp.idemployee=empd.idemployee ";
            // }
            // 
            if($depart!=null){
                if($serv==null){
                    $req.=" JOIN services serv ON emp.idemployee=serv.idservice JOIN departement dep ON serv.idservice=dep.iddepart";
                }else{
                    $req.="JOIN services serv ON emp.idemployee=serv.idservice";
                }               
            }
            elseif($serv!=null){
                $req.="JOIN services serv ON emp.idemployee=serv.idservice";
            }
            //      
            $where=[];       
            if($marital!=null || !$depart==null || $serv!=null|| $child!=null){
                $req.="WHERE";
                if($marital!=null){
                    $req.="emp.matitalStatus=? AND ";
                    $where = [$marital];
                }
                if($depart!=null){
                    if($serv==null){
                        $req.="dep.iddepart=:idDep";
                    array_merge($where , $depart);
                    }else{
                        $req.="serv.idservice=:idServ";
                    }
                }
                elseif($serv!=null){
                    if($marital!=null){
                        $req.="serv.idservice=? ";
                        array_merge($where, $serv);
                    }
                }
                // if($child!=null){
                //     $req.="serv.idservice=:idServ";
                // }
            }
            if($userid!=null){
                $req.=" WHERE emp.idemployee=? OR emp.email=? OR emp.phone LIKE '". $userid."%'";
                $where = [$userid,$userid];
            }
            $req.="ORDER BY emp.idemployee DESC";               
                
            try{
                $q=$this->bdd->query($req,$where);                
                return json_encode($q->result()); 
            }catch(Exception $ex){
                echo "error_getAgent=>".$ex->getMessage();
            }            
        }
        function getdepartement($uniq=false){
            $req="SELECT id_dep,dep_name FROM departement ";            
            try{
                $q=$this->bdd->prepare($req);                            
                $q->execute();
                $res=$q->fetchAll();
                echo json_encode($res);
            }catch(Exception $ex){
                echo "error_get departement=>".$ex->getMessage();
            }            
        }
        function getDetaillAgent(int $id=null){
            $employee=array();
            $req="SELECT emp.idemployee, emp.Fname, emp.Lname, emp.birthday, emp.sexe, emp.email, emp.phone,
                    emp.maretalStatus, serv.serviceName,grd.gradeName,grd.netsalary,grd.childprime                    
                    FROM employee emp 
                    LEFT JOIN workOnAs work ON emp.idemployee=work.idemployee 
                    LEFT JOIN services serv ON work.idservice=serv.idservice 
                    LEFT JOIN grade grd ON work.idgrade=grd.idgrade
                    LEFT JOIN primeongrade prOn ON grd.idgrade=prOn.idgrade
                    LEFT JOIN employeeprime empr ON prOn.idprime=empr.idprime
                    WHERE emp.idemployee=?";            
            try{
                $where=[$id];

                $q=$this->bdd->query($req,$where); 
                $res=json_encode($q->result());
                $res=json_decode($res);
                while($res){
                    $netSalaty= (int)$res['netsalary'];
                    $childprime= (int)$res['childprime'];
                    $totalChild=$this->getTotalChild($res['idemployee'],null);
                    $maretalStatus= $this->getPrimeEmployee($res['maretalStatus']);
                    $salary=$netSalaty+($childprime*$totalChild)+$maretalStatus;
                    $empdepend=$this->getTotalChild($res['idemployee'],"all");
                    array_push($employee,[
                        "idemployee"=>$res['idemployee'],
                        "Fname"=>$res['Fname'],
                        "Lname"=>$res['Lname'],
                        "birthday"=>$res['birthday'],
                        "sexe"=>$res['sexe'],
                        "email"=>$res['email'],
                        "phone"=>$res['phone'],
                        "maretalStatus"=>$res['maretalStatus'],
                        "servicesWork"=>$res['serviceName'],
                        "levelGrade"=>$res['gradeName'],
                        "salary"=>$salary
                    ]);
                }
                echo json_encode(array($employee,$empdepend));
            }catch(Exception $ex){
                return ["error_get typeofFees=>".$ex->getMessage()];
            }            
        }
        // 
        private function getPrimeEmployee(int $typeEmp){            
            try{
                $req=$this->bdd->get("employeeprime")->where(["typeprime","=", $typeEmp])->result();   
                if($req){                                       
                    return (int)$req->mountPrime;
                }return [];           
            }catch(Exception $ex){
                return ["error_getPrimeEMployee MaratalStatus"=>$ex->getMessage()];
            } 
        }
        // 
        private function getTotalChild(int $idEmp,$type=null){
            $sql= "SELECT `dependName`, `dependStatus`, `birthday`, `sexe` FROM empDependancy WHERE idemployee=? ";
            if($type==null){
                $sql="SELECT iddep FROM empDependancy WHERE idemployee=? AND dependStatus='child'";
            }
            try{
                $q=$this->bdd->query($sql,[$idEmp]);  
                $res=json_encode($q->result());
                $res=json_decode($res);
                $result=[];
                if($type==null){
                    $len=count($res);
                    $result=$res;
                }
                if($type!=null){
                    $depend=array();
                    $len=count($res);
                    for($i=0;$i<$len;$i++){
                        array_push($depend,array(
                            "depName"=>$res['dependName'],
                            "depRelat"=>$res['dependStatus'],
                            "depbirthday"=>$res['birthday'],
                            "depsexe"=>$res['sexe']
                        ));
                    }
                    $result=$depend;
                }
                return $result;
            }catch(Exception $ex){
                return ["error_getTotal childEmployee"=>$ex->getMessage()];
            }
        }
        // 
        function adduser($employee_field,$workon_field){
            // $req="INSERT INTO FROM employee (0, `Fname`, `Lname`, `birthday`, `sexe`, `email`, `phone`, `maretalStatus`, `datecreate`) VALUES (:Fname, :Lname, :birthday, :sexe, :email, :phone, :maretalStatus, CURRENT_TIMESTAMP))";
            // $setgrade="INSERT INTO `workonas` (`id`, `idemployee`, `idservice`, `idgrade`, `datecreate`) VALUES 
            // (0, :iduser, :idServ, :idgrade, CURRENT_TIMESTAMP)";
            try{
               
                $this->bdd->insert("employee", $employee_field)->result();  
                if($this->bdd->error()){
                    throw new Exception("$this->bdd->error()");
                }
                $userid=$this->bdd->lastId();
                array_push($workon_field,["idemployee"=>$userid]);
                try{
                    $this->bdd->insert("workonas", $workon_field)->result();
                    if ($this->bdd->error()) {
                        throw new Exception("$this->bdd->error()");
                    }
                    return $userid; 
                }catch(Exception $ex){
                    return ["error_getPrimeEMployee MaratalStatus=>" . $ex->getMessage()];
                }
                
            }catch(Exception $ex){
                return ["error_getPrimeEMployee MaratalStatus=>".$ex->getMessage()];
            } 
        }        
    }
?>