<?php
    $route->get('/users',function(){
        Response::send(md5('123456'));
    });    
    $route->post('/users/login', "userCtrl#connexion");
?>