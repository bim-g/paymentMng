<?php
    namespace Wepesi\App;

use Exception;
use Wepesi\App\Core\DB;

class Pagination extends appException{
        function __construct()
        {
            $this->db=DB::getInstance();
        }

        function getTotalRows(string $tableName, int $limitSize,int $target_offset,string $sqlQuery=null):object{
            try{
                $resudlt_query="";
                if($sqlQuery){
                    $resudlt_query=$this->db->query($sqlQuery)->result();
                }else{
                    $resudlt_query=$this->db->get($tableName)->result();
                }
                if($this->db->error()){
                    throw new Exception($this->db->error());
                }
                $total_count=count($resudlt_query);
                // check the target offset is > than 0
                $target_offset = $target_offset > 0 ? $target_offset : 1;
                $start = ($target_offset - 1) * $limitSize;
                // check if the size limit is > 0
                $limit = $limitSize > 0 ? $limitSize : 1;
                $pages=ceil($total_count / $limit);
                return (object)["pages"=>$pages,"target"=> $start];
            }catch(Exception $ex){
                $this->exception($ex);
            }
        }
    }
