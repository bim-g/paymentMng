<?php
    header("Access-Control-Allow-Origin: *");
    include '../config/connection.php';
    include_once '../class/configurations.php';

    $id=NULL;
    $name=NULL;
    $dest=NULL;


    if(isset($_POST['config'])){
        switch($_POST['config']){
            case 'addDepartement':
            $name=$_POST['Namedepart'];
            $conf = new configurations($connexion,$id,$name);
            $conf->addDepartement();
            break;
            case 'addServices':
            $id=$_POST['idDepart'];
            $name=$_POST['NameService'];
            $conf = new configurations($connexion,$id,$name);
            $conf->addServices();
            break;
        }
    }
    if(isset($_GET['config'])){
        switch($_GET['config']){
            case 'getServices':
            $conf = new configurations($connexion,$id,$name);
            $conf->getServices($_GET['mysrc']);
            break;
            case 'getDepartment':
            $conf = new configurations($connexion,$id,$name);
            // $conf->getDepartment();
            break;
            case 'getGrade':
            $conf = new configurations($connexion,$id,$name);
            $conf->getGrade();
            break;
            case 'deleteDepartment':
            $id=$_GET['idDepart'];
            $conf = new configurations($connexion,$id,$name);
            $conf->deleteDepartment();
            break;
            case 'deleteServices':
            $id=$_GET['idServices'];
            $conf = new configurations($connexion,$id,$name);
            $conf->deleteServices(null);
            break;
            case 'getConfigSalary':
            $conf = new configurations($connexion,$id,$name);
            $conf->getConfigSalary();
            break;
        }   
    }