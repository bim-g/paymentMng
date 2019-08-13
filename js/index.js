var app = angular.module("myApp", ["ngRoute"]);

app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "template/home.html",
        controller:"homeCtrl"
    })
    .when("/addAgent", {
        templateUrl : "template/addAgent.html",
        controller:"addAgentCtrl"
    })    
    .when("/detailEmployee", {
        templateUrl : "template/detailEmployee.html",
        controller:"detailEmployeeCtrl"
    })    
    .when("/detailEmployee/:id", {
        templateUrl : "template/detailEmployee.html",
        controller:"detailEmployeeCtrl"
    })    
    .when("/listEmployee", {
        templateUrl : "template/listEmployee.html",
        controller:"employeeListCtrl"
    })
    .when("/config", {
        templateUrl : "template/config.html",
        controller:"congigCtrl"
    })
    .when("/login", {
        templateUrl : "template/login.html"
    });
});

function myFunction() {
    var x = document.getElementById("Demo");
    if (x.className.indexOf("w3-show") == -1) {  
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}