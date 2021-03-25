<?php
    // Controller::useController("departementCtrl");
    $route->get('/departements',"departementCtrl#getAllDepartements");
    $route->get('/departements/services',"departementCtrl#getServices");
    $route->get('/departements/grades',"departementCtrl#getGrades");
    $route->get('/departements/salary', "departementCtrl#getSalary");
    // 
    $route->get("/departement/add", "new departementCtrl#addDepartement");