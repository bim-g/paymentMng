<?php    
    class Input{
        
        static function exists($type="POST"){
            switch($type){
                case "POST":
                    return (!empty($_POST))|| put()?true:false;
                break;
                default: 
                    return false;
                break;
            }
        }
        static function get($item){   
            $object_data= put();
            if(isset($_POST[$item])){
                return $_POST[$item];
            }else if(isset($_GET[$item])){
                return $_GET[$item];
            }else if(isset($object_data[$item])){
                return $object_data[$item];
            }
            return "";
        }
        static function header($item){            
            $headers = getallheaders();
            if(isset($headers[$item])){
                return $headers[$item];
            }
            return false;
        }
        static function post(){
            return (!empty($_POST))?$_POST:put();
        }
    }
