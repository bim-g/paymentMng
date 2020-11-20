# Payment Management v2.0
this is payment management web app develloped
# FrondEnd
in angularjs ,w3.css

# BackEnd
php with #WEPESI library
this library help design mvc web application in simple way of doing things.
there are 3 important folder:
* route : where you define all the route, and call a specific controller (class) methode to execute an operation, on where all validation took place before do an operation.
* controller : where you define all your controller, and where you can call all you model (class) with interact with the database
* class: on this folder there are one folder `app`, the one you dont need care about(`don't modify if you dont know what you are doing`), this folder is the core of the libray,
         then, can create your file out of that folder. id where you will find class of model.

# route
Is where you define all you route to reach
find out all route define to do operation on the `index.php`
```php
    <?php
    $route=new Router(); // create new instance of the router
    //create the default `GET` route when reaching the home routing
    $route->get('/',function(){ 
        // echo "welcom to the beggining";
        Response::send("welcome to the Payment Management API"); // return a wecom message whille connect
    
    });
    //include other routing for a better accessibility
    //user routing
    include("user.php"); 
    include("departement.php");

    $route->run(); //run the routing method to be execute
?>
```
as you can see on this exemple bellow. it provide example on how the libray. for more detail you can check wepesy libray documentation.
```php
    <?php 
        $route->post('/users/login', "userCtrl#connexion");      
    ?>
```
This a simple example of the login route to log a user `/users/login`,
`userCtrl#connexion` is a methode has been call to execute the operation on the controller.
as you can see with have the class `userCrl` and inside we have the methode `connexion` that help to connect a user. 


#
# DataBASE
find out a file name `sentinel`, import it into your your database call `sentinel` phpmyadmin.
you can change the database name.
#change Database configuration
to change the database name with your own.
go into `api\config\global.php` from there you can change
```php
    define("HOST", "localhost"); //you set the host: where is locate your server. for this exemple is on localhost
    define("DATABASE", "sentinel"); // `sentinel` is the database by default.
    define("USER", "root"); //`root` is the user by default you can changer with yours
    define("PASSWORD", ""); //the password is empty by default
```
