<?php
    header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");

    include '../config/connection.php';
    include_once '../class/users.php';

    $id=NULL;
    $fname=NULL;
    $lname=NULL;
    $sex=NULL;
    $type=NULL;
    $phone=NULL;
    $birth=NULL;
    $mail=NULL;
    $marital=NULL;
    $psdo=NULL;
    $pswd=NULL;
    
    
    if(isset($_POST['user'])){
        switch($_POST['user']){
            case 'adduser':
            $fname=$_POST['fname'];
            $lname=$_POST['lname'];
            $sex=$_POST['sexe'];
            $type=$_POST['type'];
            $phone=$_POST['phone'];
            $birth=$_POST['birth'];
            $mail=$_POST['mail'];
            $marital=$_POST['marital'];
            $user = new users($connexion,$id,$fname,$lname,$sex,$type,$mail,$marital,$phone,$birth,$psdo,$pswd);
            $user->adduser($_POST['idgrade'],$_POST['idDepart']);
            break;
        }
    }
    
    if(isset($_GET['agent'])){
        switch($_GET['agent']){
            case 'getAgent':
            $user = new users($connexion,$id,$fname,$lname,$sex,$type,$mail,$marital,$phone,$birth,$psdo,$pswd);
            $user->displayEmployees($_GET['keyword'],null,null,null,null);
            break;
            case 'getDetaillAgent':
            $id=$_GET['userId'];
            $user = new users($connexion,$id,$fname,$lname,$sex,$type,$mail,$marital,$phone,$birth,$psdo,$pswd);
            $user->getDetaillAgent($id);
            break;
            case 'getTransactions':
            $user = new users($connexion,$id,$fname,$lname,$sex,$type,$mail,$marital,$phone,$birth,$psdo,$pswd);
            // $user->gettypeOfFees();
            break;
        }        
    }   
    
?>