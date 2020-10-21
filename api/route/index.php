<?php
    $route=new Router();
    $route->get('/',function(){
        // echo "welcom to the beggining";
        Response::send("welcome to the begining");
    
    });
    //user routing
    include("user.php");

    $route->run();
?>