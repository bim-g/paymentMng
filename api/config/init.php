<?php
    session_start();
    
    require_once 'global.php';
    //
    $GLOBALS['config']=array(
        'mysql'=>array(
            'host'=> HOST,
            'username'=> USER,
            'password'=> PASSWORD,
            'db'=> DATABASE
        ),
        'remender'=>array(),
        'session'=>array(
            "token_name"=>"token"
        )
    );

    function getSubDirectories($dir)    {
        $subDir = array();
        $directories = array_filter(glob($dir), 'is_dir');
        $subDir = array_merge($subDir, $directories);
        foreach ($directories as $directory) $subDir = array_merge($subDir, getSubDirectories($directory . '/*'));
        return $subDir;
    }

    // will load all class from the class folder
    spl_autoload_register(function($class){
        $dirs = getSubDirectories("class");
        $class_arr=explode("\\",$class);
        $len=count($class_arr);
        $classFile=$class_arr[($len-1)];
        foreach($dirs as $dir){
            $file=$dir."/". checkFileExtension($classFile);
            if (is_file($file)) { // check if the file exist
                require_once($file); // incluse the file request if it exist
            }
        }
    });

    function checkFileExtension($fileName){
        $file_parts = pathinfo($fileName);
        $file = (isset($file_parts['extension']) && $file_parts['extension'] == "php") ? $fileName : $fileName . ".php";
        return $file;
    }
    require_once 'controller/function.php';
