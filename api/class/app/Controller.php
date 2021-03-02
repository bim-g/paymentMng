<?php
    class Controller{
        
        private static function useModel($file)        {
            if (is_file(ROOT . 'corp/' . $file . ".php")) {
                require_once(ROOT . 'corp/' . $file . '.php');
            }
        }
        static function useController($fileName)        {
            // get sub directorie of the project
            $directories = getSubDirectories("controller");
            foreach ($directories as $dir) {
                // create the file path
                $file = $dir . "/" . checkFileExtension($fileName);
                if (is_file($file)) {
                    require_once($file);
                }
            }
        }       
    }
?>