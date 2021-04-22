<?php   
    require_once('config/cors.php');
    require_once 'config/init.php';
    // manage cors for API Request
    cors();
    require_once 'route/index.php';
?>