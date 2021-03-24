<?php
    /**
     * allow configuration are store in this file
     * for database and and the connection 
     * they declare as global tho to have full acces where ever in the project
     */
    // load configguration 
    $ini_array =(object) parse_ini_file("./config/config.ini", true);
    $db_conf= (object)$ini_array->db_conf;

    // database configuration setup
    define("HOST", $db_conf->host);
    define("DATABASE", $db_conf->database);
    define("USER", $db_conf->user);
    define("PASSWORD", $db_conf->password);
    define("LANG","fr");
    // inlude language file according to your configuraiton
    include("./lang/" . checkFileExtension(LANG));
    define("LANG_VALIDATE", $validation);
    define("LANG_BOX_MESSAGE", $boxMessage);

    //web root configaration

    define('WEB_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
    define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
