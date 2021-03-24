<?php
    Controller::useController("departementCtrl");
    $route->get('/departements',(new departementCtrl)->getAllDepartements());
    $route->get('/departements/services',(new departementCtrl)->getServices());
    $route->get('/departements/grades',(new departementCtrl)->getGrades());
    $route->get('/departements/salary', (new departementCtrl)->getSalary());
    // 
    $route->get("/departement/add", (new departementCtrl)->addDepartement());