<?php
    class users{
        private $idUser;
        private $Fname;
        private $Lname;
        private $Sexe;
        private $Type;
        private $Phone;
        private $email;
        private $maretal;
        private $birthday;
        private $Pseudo;
        private $Passwd;
        private $bdd;

        function __construct()
        {
            $this->bdd=DB::getInstance();            
        }
       
        function connexion($data=[]){
            $sql="SELECT * FROM users us JOIN employee em ON us.idemployee=em.idemployee  WHERE (em.email=? OR us.username=?) AND us.passwd=?";
            try{
                $res=$this->bdd->query($sql,$data);  
                if($res->result())  {
                    return json_encode($res->result());
                }            
                 return false;
            }catch(Exception $ex){
                 return ["Exception"=>$ex->getMessage()];
            }            
        }
        function displayEmployees($key=null,$marital=null,$depart=null,$serv=null,$child=null){
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
            if($marital!=null || !$depart==null || $serv!=null|| $child!=null){
                $req.="WHERE";
                if($marital!=null){
                    $req.="emp.matitalStatus=:marital AND ";
                }
                if($depart!=null){
                    if($serv==null){
                        $req.="dep.iddepart=:idDep";
                    }else{
                        $req.="serv.idservice=:idServ";
                    }
                }
                elseif($serv!=null){
                    if($marital!=null){
                        $req.="serv.idservice=:idServ ";
                    }
                }
                // if($child!=null){
                //     $req.="serv.idservice=:idServ";
                // }
            }
            if($key!=null){
                $req.=" WHERE emp.idemployee=:mykey OR emp.email=:mykey OR emp.phone LIKE '".$key."%'";
            }
            $req.="ORDER BY emp.idemployee DESC";
            
            try{
                $q=$this->bdd->prepare($req);
                if($key!=null ){
                    $q->bindParam(":mykey",$key);
                }
                if($marital!=null ){
                    $q->bindParam(":marital",$$marital);
                }
                if(!$depart==null ){
                    $q->bindParam(":idDep",$depart);
                }
                if($serv!=null){
                    $q->bindParam(":idServ",$serv);
                }
                // if($child!=null){
                //     $q->bindParam($child);
                // }
                              
                $q->execute();
                $res=$q->fetchAll();                
                echo json_encode($res);
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
        function getDetaillAgent($uniq=false){
            $employee=array();
            $req="SELECT emp.idemployee, emp.Fname, emp.Lname, emp.birthday, emp.sexe, emp.email, emp.phone,
                    emp.maretalStatus, serv.serviceName,grd.gradeName,grd.netsalary,grd.childprime                    
                    FROM employee emp 
                    LEFT JOIN workOnAs work ON emp.idemployee=work.idemployee 
                    LEFT JOIN services serv ON work.idservice=serv.idservice 
                    LEFT JOIN grade grd ON work.idgrade=grd.idgrade
                    LEFT JOIN primeongrade prOn ON grd.idgrade=prOn.idgrade
                    LEFT JOIN employeeprime empr ON prOn.idprime=empr.idprime
                    WHERE emp.idemployee=:idemp";            
            try{
                $q=$this->bdd->prepare($req); 
                $q->bindParam(":idemp",$this->idUser);                           
                $q->execute();
                while($res=$q->fetch()){
                $salary=(int)$res['netsalary']+((int)$res['childprime']*$this->getTotalChild($res['idemployee'],null))+$this->getPrimeEmployee($res['maretalStatus']);
                $empdepend=$this->getTotalChild($res['idemployee'],"all");
                    array_push($employee,array(
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
                    ));
                }
                echo json_encode(array($employee,$empdepend));
            }catch(Exception $ex){
                echo "error_get typeofFees=>".$ex->getMessage();
            }            
        }

        private function getPrimeEmployee($typeEmp){
            $req="SELECT mountPrime FROM employeeprime WHERE typeprime=:typestatus";
            try{
                $q=$this->bdd->prepare($req);  
                $q->bindParam(":typestatus",$typeEmp) ;                         
                $q->execute();
                $res=$q->fetch();

                return (int)$res['mountPrime'];
            }catch(Exception $ex){
                echo "error_getPrimeEMployee MaratalStatus=>".$ex->getMessage();
            } 
        }

        private function getTotalChild($idEmp,$type=null){
            if($type==null){
                $req="SELECT iddep FROM empDependancy WHERE idemployee=:idemp AND dependStatus='child'";
            }else{
                $req="SELECT `dependName`, `dependStatus`, `birthday`, `sexe` FROM empDependancy WHERE idemployee=:idemp ";
            }
            try{
                $q=$this->bdd->prepare($req);  
                $q->bindParam(":idemp",$idEmp) ;                         
                $q->execute();
                if($type==null){
                    $res=count($q->fetchAll());
                    $result=(int)$res;
                }
                if($type!=null){
                    $depend=array();
                    while($res=$q->fetch()){
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
                echo "error_getTotal childEmployee=>".$ex->getMessage();
            }
        }

        function adduser($idServ,$idDep){
            $req="INSERT INTO FROM employee (0, `Fname`, `Lname`, `birthday`, `sexe`, `email`, `phone`, `maretalStatus`, `datecreate`) VALUES (:Fname, :Lname, :birthday, :sexe, :email, :phone, :maretalStatus, CURRENT_TIMESTAMP))";
            $setgrade="INSERT INTO `workonas` (`id`, `idemployee`, `idservice`, `idgrade`, `datecreate`) VALUES 
            (0, :iduser, :idServ, :idgrade, CURRENT_TIMESTAMP)";
            try{
               
                $q=$this->bdd->prepare($req);  
                $q->bindParam(":Fname",$this->Fname) ;                         
                $q->bindParam(":Lname,",$this->Lname) ;                         
                $q->bindParam(":birthday, ",$this->birthday) ;                         
                $q->bindParam(":sexe, ",$this->Sexe) ;                         
                $q->bindParam(":email, ",$this->email) ;                         
                $q->bindParam(":phone",$this->Phone) ;                         
                $q->bindParam(":maretalStatus",$this->maretal);                   
                $q->execute();
                $res=$q->fetch();

                return (int)$res['mountPrime'];
            }catch(Exception $ex){
                echo "error_getPrimeEMployee MaratalStatus=>".$ex->getMessage();
            } 
        }
        
    }
?>