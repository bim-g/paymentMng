<?php
    $route=new Router();
    $route->get('/',function(){
        // echo "welcom to the beggining";
        Response::send("welcome to the Payment Management API");
    
    });
    //user routing
    include("user.php");
    include("departement.php");

    $route->run();
?>