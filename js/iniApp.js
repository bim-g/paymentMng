app.controller("initApp",function($scope,$location,$window,initApp){
	$scope.about=false;
	$scope.logout=false;
	initApp.initilize();
	$scope.connect=false;

	$scope.logout=function(){
		if(confirm("Would you like to log out?")){
			$window.sessionStorage.clear();
			initApp.initilize();
			$scope.menu=false;
		}	
	};
	$scope.$on("detailAgent",function(e){
		$scope.activebtn='detailAgent'
	});
	$scope.$on("allAgent",function(e){
		$scope.activebtn='allAgent'
	});
	$scope.$on("newAgent",function(e){
		$scope.activebtn='newAgent'
	});
	$scope.$on("config",function(e){
		$scope.activebtn='config'
	});

	$scope.$on('login',function(e){
		initApp.initilize();
		$scope.menu=true;
	});
	if($window.sessionStorage.menu){
		$scope.menu=true;
	}
	
	$scope.setactive=function(menu){
		if(!$window.sessionStorage.activebtn){
			$window.sessionStorage.setItem("activebtn",menu);
		}else{
			$window.sessionStorage.activebtn=menu;
		}
		$scope.activebtn=$window.sessionStorage.activebtn;	
	};
	$scope.setactive("");
});

app.controller("login",function($scope,initApp){
	$scope.connection=function(){
		if(!angular.isUndefined($scope.loginUser)){
			initApp.connection($scope.loginUser,function(r){
				if(r=="connect"){
					initApp.initilize();
					$scope.$emit('login');
				}else{
					alert(r);
				}
			});			
		}
	};
});

app.controller("homeCtrl",function($scope,$location,initApp){
    $scope.nodata=false;    
    $scope.researchdata=false;   
    
    $scope.searchAgentt=function(){
		$scope.nodata=false;
		if(!angular.isUndefined($scope.keyword) && $scope.keyword!=""){
			initApp.searchAgent($scope.keyword,function(r){
				if(angular.isObject(r) && !angular.isUndefined(r)) {
					$scope.agents=r;
					$scope.getdata=r.length;
					if(r.length<1){
						$scope.nodata=true;
					}
				}else{
					alert(r);
					$scope.nodata=false;
				}
			});
		}else{
			alert("Enter Matricul number or email to rearch");
		}	   
    };
    $scope.fetchLink=function(url){
        if(url){
            $location.path(url);
        }
    };
    $scope.mylogin=true;
});
app.controller("addAgentCtrl",function($scope,$location,initApp){
	$scope.$emit("newAgent");
    $scope.newstudent=false;    
    $scope.AddEmployee=function(){
        console.log($scope.emloyee);
        // initApp.AddEmployee($scope.emloyee,function(r){
        //     if(r=="add student"){
        //         $scope.newstudent=false;
        //         $scope.student.fname=null;
        //         $scope.student.lname=null;
        //         $scope.student.sexe=null;
        //         $scope.student.phone=null;
        //         $scope.student.email=null;
        //         alert("Student Add successfully");
        //     }else{
        //         alert(r);
        //     }
        // }); 
    };
    
        initApp.getServices("services",function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.Myservices= r;
                console.log(r)
            }else{
                alert(r);
            }
        });	
        initApp.getGrade(function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.grades= r;
                console.log(r)
            }else{
                alert(r);
            }
        });	
    
    // initApp.getdepartement(function(r){
    //     if(angular.isObject(r) && !angular.isUndefined(r)){
    //         $scope.departement=r;
    //     }else{
    //         alert(r);
    //     }
    // });
    $scope.searchStudent=function(){
        console.log($scope.rearch);
        //$scope.fetchLink("search");
    };
    // fetch link
    $scope.fetchLink=function(url){
        if(url){
            $location.path(url);
        }
    };
    $scope.mylogin=true;
});
app.controller("employeeListCtrl",function($scope,initApp){
	
	$scope.$emit("allAgent");
    initApp.searchAgent("",function(r){
        if(angular.isObject(r) && !angular.isUndefined(r)){
            $scope.employees=r;
        }else{
            alert(r);
        }
    });

    $scope.getinfosStudent=function(){
        initApp.studentinfos(function(){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.students=r;
            }else{
                alert(r);
            }
        });
    };
});
app.controller("detailEmployeeCtrl",function($scope,$window,$routeParams,initApp){
	$scope.rollnumber;    
	
	$scope.$emit("detailAgent");
    $scope.getTransaction=function(iduser){
        initApp.getTransactions(iduser,function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.transactions=r;
                $scope.transact=r.length;
            }else{
                console.log(r);
            }
        });
    };
    $scope.searchAgent=function(){
        if(Number($scope.idAgent)){
            initApp.getDetaillAgent($scope.idAgent,function(r){
                if(angular.isObject(r) && !angular.isUndefined(r)){
					var employee=r[0];
					var famill=r[1];
                    $scope.rollnumber=$scope.idAgent;
                    $scope.empname=angular.uppercase(employee[0].Fname+" "+employee[0].Lname);
                    $scope.empsexe=employee[0].sexe;
                    $scope.empphone=employee[0].phone;
                    $scope.empemail=employee[0].email;
                    $scope.empbirth=employee[0].birthday;
                    $scope.empmaretal=employee[0].maretalStatus;
                    $scope.Level=employee[0].levelGrade;
                    $scope.services=employee[0].servicesWork;
                    $scope.totalSalary=employee[0].salary;
					// $scope.getTransaction($scope.idstudent);
					console.log(employee[0])
					$scope.familly=famill;
					$scope.nbenfant=famill.length;
				}
                else{
                    alert(r);
                }
            });
        }else{
            alert("enter a number");
        }
    };
    if($routeParams.id){
        if(Number($routeParams.id)){
            $scope.idAgent=Number($routeParams.id);
            $scope.searchAgent();
        }
        
    }
    $scope.studentpayed=function(){
        $scope.payment={
            bankproof:$scope.finBank,
            amount:$scope.finamount,
            idtypeFess:$scope.finTypes,
            idstudent:$scope.rearch.idstudent,
            iduser:$window.sessionStorage.userId
        }
        initApp.studentPay($scope.payment,function(r){
            if(r=="success pay"){
                $scope.finBank=null;
                $scope.finamount=null;
                $scope.finTypes=null;
                $scope.searchStudent();
            }
            else{
                alert(r);
            };
        });
    };
});
app.controller("congigCtrl",function($scope,initApp){
    $scope.myconfig="departement";
    $scope.editDepartment=false;
    $scope.$emit("config");
    $scope.getDepart=function(){
        initApp.getDepartment(function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.departement= r;
            }else{
                alert(r);
            }
        });	
    }
    $scope.getDepart();
    $scope.displayServices=function(){
        initApp.getServices("services",function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.services= r;
            }else{
                alert(r);
            }
        });	
    }
    $scope.displayServices();
    $scope.addDepartement=function(){
        var AddDepartName=$("AddDepartName").value;
        if(!angular.isUndefined(AddDepartName) && AddDepartName!=""){
            initApp.addDepartement(AddDepartName,function(r){
                if(r=="success depart"){
                    alert("Departement add successfully");
                    $scope.AddDepartName=null;
                    $scope.getDepart();
                }else{
                    console.log(r);
                }       
            }); 
        }else{
            alert("Enter a departement");
        }
    };
    $scope.deleteDep=function(id){        
        if(confirm("would like to delete these Departement?\nit will delete alse services related to it")){
            initApp.deleteDepartment(id,function(r){
                if(r=="delete_deport success"){
                    $scope.getDepart();
                    alert("Department Delete Succesfully");
                }else{
                    alert(r)
                }
            })
        }
    };
    $scope.addServices=function(){
        var id=$("servidepart").value
        var name =$("servName").value;
        if((!angular.isUndefined(id) && Number(id)) && (!angular.isUndefined(name) && name!="")){
            var serv={
                idDepart:id,
                servName:$("servName").value
            }            
            initApp.addServices(serv,function(r){
                if(r=="success revices"){
                    $scope.displayServices();
                    alert("Service add Succesfully");
                }else{
                    alert(r);
                }
            });
        }else{
            alert("You cant Empty services or Services to empty Departement");
        }
    };
    $scope.deleteServices=function(id){
        if(confirm("would like to delete these Services?")){
            initApp.deleteDepartment(id,function(r){
                if(r=="delete_deport success"){
                    $scope.displayServices();
                    alert("Department Delete Succesfully");
                }else{
                    alert(r);
                }
            });
        }
    };
    $scope.getConfigSalary=function(){
        initApp.getConfigSalary(function(r){
            $scope.confSal=r;
        });
    };
    $scope.getConfigSalary();
});
function $(id){return document.getElementById(id);}