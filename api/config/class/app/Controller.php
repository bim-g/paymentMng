<?php

namespace Wepesi\App\Core;
    class Controller{
        
        private static function useModel($filename){
            $file = checkFileExtension($filename);
            if (is_file(ROOT . 'corp/' . $file)) {
                require_once(ROOT . 'corp/' . $file );
            }
        }
        static function useController($filename){
            // get sub directorie of the project
            $directories = getSubDirectories("controller");
            foreach($directories as $dir){
                // create the file path
                $file=$dir."/". checkFileExtension($filename);
                if (is_file( $file )) {
                    require_once($file );
                }
            }
        }       
    }
