<?php

namespace Wepesi\App\Core;
use Wepesi\App\Core\Language;
    class Validate{
        private $_passed=false;
        private $_errors=false;
        private $db;
        private $lang=null;
        function __construct()
        {
            $this->db=DB::getInstance();
            $this->lang= (object)LANG_VALIDATE;
        }

        function check($source,$items=array()){
            foreach($items as $item=>$rules){
                foreach($rules as $rule=>$rvalue){
                    if(isset($source[$item])){
                        $value=trim($source[$item]);
                        $item=escape($item);
                        
                        if($rule=='required' && empty($value)){
                            $this->addError("{$item} ".$this->lang->required);
                        }else if(!empty($value)){
                            switch($rule){
                                case "min":
                                    if(strlen($value)<$rvalue){
                                        $this->addError("{$item} ". $this->lang->min." {$rvalue} caracters");
                                    }
                                break;
                                case "max":
                                    if(strlen($value)>$rvalue){
                                        $this->addError("{$item} ".$this->lang->max." {$rvalue} caracters");
                                    }
                                break;
                                case "matches":
                                    if($value!=$source[$rvalue]){
                                        $this->addError("{$rvalue} " . $this->lang->matches . " {$item}");
                                    }
                                break;
                                case "number":
                                    if(preg_match("#.\W#",$value) || preg_match("#[a-zA-Z]#",$value)){
                                        $this->addError("{$item} " . $this->lang->number );
                                    }
                                break;
                                case "email":
                                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                        $this->addError("{$item} " . $this->lang->email );
                                    }
                                break;
                                case "url":
                                    if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $source[$rvalue])){
                                        $this->addError("{$item} " . $this->lang->url);
                                    }
                                case "file":
                                    if(!is_file($source[$rvalue])){
                                        $this->addError("{$item} " . $this->lang->file);
                                    }
                                break;
                                case "unique":
                                    $check=$this->db->get($rvalue)->where([$item,'=',$value])->result();
                                    if(count($check)){
                                        $this->addError("{$item}" . $this->lang->unique);
                                    }
                                break;
                            }
                        }
                    }else{
                        $this->addError("{$item} " . $this->lang->required);
                    }  
                }
            }
            if(empty($this->_errors)){
                 $this->_passed=true;
            }
        }

        function addError($error){
            $this->_errors[]=$error; 
        }

        function errors(){
            return $this->_errors;
        }

        function passed(){
            return $this->_passed;
        }
    }
