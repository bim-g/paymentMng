<?php
    $route->get('/users', "userCtrl#getAllEmployee");    
    $route->get('/users/:id/detail', "userCtrl#getEmployeDetail");
    $route->post('/users/login', "userCtrl#connexion");

    ?>