<?php
namespace Wepesi\App;
// include class from namespace
use Wepesi\App\Core\{
    DB,
    Hash
};
// use FFI\Exception;
use Exception;
// use FFI\Exception;
class Users extends appException{
        private $db;
        private $iduser;
        function __construct()
        {
            $this->db=DB::getInstance();
        }
        function setid($id){
            $this->iduser=$id;
        }
        function getID(){
            return $this->iduser;
        }
        // 
        function connexion($username,$pwd){
            try{
                $whereSalt= ["email", "=", $username];
                if(!$this->findSalt($whereSalt)){
                    return false;
                }
                $salt= $this->findSalt($whereSalt)->salt;
                $password= Hash::make($pwd,$salt);                
                $user=$this->db->get("users")->where([
                    ["email", "=", $username],
                    ["password", "=", $password]
                ])->result();
                if($user){
                    // check if the user have an avatar                    
                    $media=$this->getAvatar($user[0]->idavatar);
                    $level=$this->getLevel($user[0]->levelid);
                    $result=array_merge((array)$user[0],$media);                          
                    $result=array_merge($result,$level); 
                    return json_encode($result);
                }
                return false;

            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        private function getAvatar($idAvatar){
            $media = $this->db->get("media")->fields(["link"])->where(["id", "=", $idAvatar])->result();
            if($media){
                return (array)$media[0];
            }
            return ["link" => null];
        }
        private function getLevel($idAvatar){
            $level = $this->db->get("userlevel")->fields(["designation"])->where(["id", "=", $idAvatar])->result();
            if($level){
                return (array)$level[0];
            }
        }
        function findSalt($where){
            try{
                $result= $this->db->get("users")->fields(["salt"])->where($where)->result();            
                if($this->db->error()){
                    throw new Exception($this->db->error()); 
                }
                if($result){
                    return $result[0];
                }
                return false;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        private function getstate($state){
            if($res=$this->db->get("state",["designation","=",$state],["idstate"])->result()){
                return (int)$res[0]->idstate;
            }
            return false;
        }
        /**
         * update user status
         * - activate an account
         * - block an account
         * @params $fields // fields to be updated
         * @params $where // fields from where condition
         */
        function  updateUserState($fields,$where){
            try{
                $this->db->update("users")->fields($fields)->where($where)->result();
                if($this->db->error()){
                    throw new exception("Unable to update user state");
                }
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
         
        function adduser(array $user){
            try{ 
                // add new user information 
                $this->db->insert("users")->fields($user)->result();
                if($this->db->error()){
                    throw new Exception("Annable to do the opertion");
                }
                // preparer information for the account table
                // get users last id to be used to inser account information
                $account=[
                    "userid"=> $this->db->lastId(),
                    "datelastupdate"=> $user['dateregister']
                ];
                // insert information on the account user table
                $this->db->insert("account")->fields($account)->result();
                if($this->db->error()){
                    throw new Exception("Annable to do the account operation");
                }
                return true;
                                
            }catch(Exception $ex){
                return $this->exception($ex);
            }  
        }
        function resetPassword($fields,$where,$pswd){
            try{
                $req= $this->db->update("users")->fields($fields)->where($where)->result();
                if($this->db->error()){
                    throw new Exception("unnable to reset the password");
                }

                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function updateuser($users,$account){              
            try{
                $this->db->update("users")->fields($users[0])->where($users[1])->result();
                if($this->db->error()){
                    throw new Exception("annable to update users");
                }               
                $this->db->update("account")->fields($account[0])->where($account[1])->result();
                if ($this->db->error()) {
                    throw new Exception("annable to update account");
                }
                return true;                               
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        } 
        private function sendEmail(){}
        private function updateaccount($_account,$where){
            try{       
                     
                if(!$this->db->update("account")->where(["iduser"=>(int)$where[0]])->fields($_account)){
                    throw new Exception("annable to update acount");
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }

        //allow to delete a user and if the is no parametres 
        //is going to clean up teh tables users and all
        function deleteUser($where){            
            try{
                $this->db->delete("users")->where($where)->result();
                if($this->db->error()){
                    throw new exception($this->db->error());
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }            
        }
        
        function getNotification($iddoc=null){
            $sql="SELECT c.idcase,CONCAT(u.fname,\" \",u.lname) as pacient,c.statecase,c.dateassign,c.datelastupdate ";
            
            if(!$iddoc){
                $sql.=" ,(SELECT CONCAT( d.fname,\" \",d.lname) as drname FROM users d JOIN cases cd ON d.id=cd.iddoctor WHERE cd.iddoctor=c.iddoctor AND cd.idpacient=c.idpacient LIMIT 1) as specialist FROM users u JOIN cases c ON u.id=c.idpacient ORDER BY c.idcase DESC";
                $result= $this->db->query($sql)->result();
                return json_encode($result);
            }
            $sql.=" FROM users u JOIN cases c ON u.id =c.iddoctor WHERE c.iddoctor=? ORDER BY c.idcase DESC";
                $result= $this->db->query($sql, [(int)$iddoc]);
                return json_encode($result);            
        }
       /**
        *  */
        function getDetailusers(int $id){
            try{
                $sql= "SELECT u.id,u.fname,u.lname,u.sexe,u.idstate,u.levelid,u.birthday,u.phonenumber,u.email,u.username,a.grade,d.designation,a.company,a.about,a.city,a.country,a.adress,m.link as avatar,l.designation as level 
                FROM users u 
                LEFT JOIN account a ON u.id=a.userid 
                LEFT JOIN usersdomaine d ON a.iddomain=d.iddomain 
                LEFT jOIN media m ON u.idavatar=m.id 
                LEFT JOIN userlevel l ON u.levelid=l.id
                WHERE u.id=?";
                $req=$this->db->query($sql,[$id])->result();                
                
                return json_encode($req);
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        function getusers(int $target=1){
            try{
                $limit=20;
                // get pagination detail the totalrows count and offset
                $pagination=(new Pagination)->getTotalRows("users",$limit,$target);
                $target =$target>0? $target:1;
                $offset = $pagination->target;
                $totalPages=$pagination->pages;
                // sql query to get all infrmation about the users
                $sql= "SELECT u.id,u.fname,u.lname,u.sexe,u.idstate,u.patientid as src,a.city,a.country,a.grade,a.company,m.link as avatar,u.dateregister,l.designation as level 
                        FROM users u 
                        LEFT JOIN account a ON u.id=a.userid 
                        LEFT jOIN media m ON u.idavatar=m.id 
                        LEFT JOIN userlevel l ON u.levelid=l.id 
                        ORDER BY u.id DESC LIMIT ? OFFSET ? ";
                $req=$this->db->query($sql,[(int)$limit,(int)$offset]);
                // get list of users with limit and offest (pagination)
                $result= [
                    "result" =>(array) $req->result(), 
                    "totalPages" => $totalPages, 
                    "activepages" => $target
                ];                
                return json_encode($result);
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }
        private function getTotaluser(){
            try{
                $req=$this->db->get("users")->result();
                return count($req);
            }catch(Exception $ex){}
        }
        private function getPages(int $total,int $size){
            try{
                $size=$size>0?$size:1;
                return ceil($total/$size);
            }catch(Exception $ex){
                $this->exception($ex);
            }
        }
        function stateCase($id,$type){
            $state=$this->_case($type);            
            try{
                if(!$this->db->update("cases",["idcase"=>$id],["statecase"=>$state, "datelastupdate"=>date('Y-m-d H:i:s')])){
                    throw new exception("enable to update");
                }                
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }            
        }
        // get case state to known what is going with the pacient
        private function _case($case){
            if($case=="WorkingOn"){
                return 1;
            }
            elseif($case=="Resolved"){
                return 2;
            }
            elseif($case=="Reject"){
                return -1;
            }
        }
    /**
     *  @param {*} fields //field to be updated
     * @param {*} where // field for where condition
     */
        function updatelevel($fields,$where){
            try{  
                $this->db->update("users")->fields($fields)->where($where)->result();              
                if($this->db->error()){
                    throw new exception("Impossible de modifier l'access de cette utilisateur");
                }
                return true;
            }catch(Exception $ex){
                return $this->exception($ex);
            }
        }

        /***
         * 
         */
        function checkManager($where){
            try{
                $req=$this->db->get("users")->fields(["levelid"])->where($where)->result();
                if($this->db->error()){
                    throw new Exception($this->db->error());
                }
                return $req;
            }catch(Exception $ex){
                $this->exception($ex);
            }
        }
        // allow to set the credention of a use
        // to define if its a admin, manager or a user
        function setCredential(){}
        
    }
?>