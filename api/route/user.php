<?php
    $route->get('/users',function(){
        Response::send("welcom to pay roll api!");
    });
    // $route->post('/users',function(){
    //     Response::send($_POST);
    // });
    $route->post('/users', "userCtrl#connexion");
?>