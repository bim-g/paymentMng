app.controller("initApp",function($rootScope,$scope,$location,$window,initApp){
    $scope.confirm=false;
    $scope.alert=false;
    $scope.danger=false;
    $scope.succes=false;
    $scope.logout=false;
    
	initApp.initilize();
	$scope.connect=false;
    $scope.aBout=function(){
        $scope.about=true;
    };
	$scope.logout=function(){		
        $scope.msg="Would you like to log out?"	;
        $scope.myfunc="gogout()";
        $scope.confirm=true;
        $scope.operation="gogout";
    };
    $scope.gogout=function(){
        $window.sessionStorage.clear();
        initApp.initilize();
        $scope.menu=false;
    };
	$scope.$on("detailAgent",function(e){
		$scope.activebtn='detailAgent';
	});
	$scope.$on("allAgent",function(e){
		$scope.activebtn='allAgent';
	});
	$scope.$on("newAgent",function(e){
		$scope.activebtn='newAgent';
	});
	$scope.$on("config",function(e){
		$scope.activebtn='config';
	});

	$scope.$on('login',function(e){
		initApp.initilize();
		$scope.menu=true;
	});
	
    $scope.conFirm=function(){
        $scope.confirm=true;
    };
    
    $scope.initModal=function(){
        $scope.alert = $scope.succes = $scope.danger = $scope.confirm = false;
    };
    $scope.aleRt=function(){
        $scope.alert=true;
    };
    $scope.danGer=function(){
        $scope.danger=true;        
    };
    $scope.sucCes=function(){
        $scope.succes=true;
    };
    $scope.$on('confirm',function(e,data){
        $scope.msg=data.msg;
        $scope.operation=data.operation;
        $scope.idvalue=data.id;
		$scope.conFirm();
    });
	$scope.$on('alert',function(e,data){
        $scope.msgAlert=data.msg;
		$scope.aleRt();
    });
	$scope.$on('succes',function(e,data){
        $scope.msg=data.msg;
		$scope.sucCes();
    });
	$scope.$on('danger',function(e,data){
        $scope.errorMsg=data.msg;
		$scope.danGer();
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
    $scope.confirmOp=function(){
        var func=$scope.operation;
        var id=$scope.idvalue;
        var data={
            func:func,
            id:id
        };
        if(func!=null){
            $scope.$broadcast("yesConfirm",data);            
        }
        $scope.operation=null;
        $scope.idvalue=null;
    };
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            // if(id!=null){
            //     //$scope[func](id);
                $scope.logout(id);
            // }else{
                //$scope[func]();
            //}
        }
    });
});

app.controller("login",function($scope,initApp){
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
	$scope.connection=function(){
		if(!angular.isUndefined($scope.loginUser)){
			initApp.connection($scope.loginUser,function(r){
                console.log(">>>>>>>>>>>>",r);
				if(r=="connect"){
					initApp.initilize();
					$scope.$emit('login');
				}else{

                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
				}
			});			
		}
	};
});

app.controller("homeCtrl",function($scope,$location,initApp){
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
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
                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
					$scope.nodata=false;
				}
			});
		}else{
            $scope.$emit('alert',{msg:"Enter Matricul number or email to rearch"});
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
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
	$scope.$emit("newAgent");
    $scope.newstudent=false;    
    $scope.AddEmployee=function(){
        console.log($scope.emloyee);        
    };
    
        initApp.getServices("services",function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.Myservices= r;
            }else{
                $scope.$emit('danger',{msg:(r.message?r.message:r)});      
            }
        });	
        initApp.getGrade(function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.grades= r;
            }else{                
                $scope.$emit('danger',{msg:(r.message?r.message:r)});       
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
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
    $scope.$emit("allAgent");
    //get list of all employee
    initApp.gestAgents(function (r) {
        if(angular.isObject(r) && !angular.isUndefined(r) && r.status==200){
            $scope.employees=JSON.parse(r.response);
        }else{            
            $scope.$emit('danger',{msg:(r.message?r.message:r)});
        }
    });

    $scope.getinfosStudent=function(){
        initApp.studentinfos(function(){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.students=r;
            }else{                
                $scope.$emit('danger',{msg:(r.message?r.message:r)});
            }
        });
    };
    $scope.search=function(){

    };
});
app.controller("detailEmployeeCtrl",function($scope,$window,$routeParams,initApp){
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
	// $scope.rollnumber; 
	$scope.$emit("detailAgent");
    $scope.getTransaction=function(iduser){
        initApp.getTransactions(iduser,function(r){
            if(angular.isObject(r) && !angular.isUndefined(r)){
                $scope.transactions=r;
                $scope.transact=r.length;
            }else{                
                $scope.$emit('danger',{msg:(r.message?r.message:r)});
            }
        });
    };
    // get detail information about the selected agent/employee
    $scope.searchAgent=function(){
        if(Number($scope.idAgent)){
            initApp.getDetaillAgent($scope.idAgent,function(r){
                if (angular.isObject(r) && !angular.isUndefined(r) && r.status == 200) {
                    r=JSON.parse(r.response);
					var employee=r[0];
                    var famill=[];
                    if(r[1]){
                        famill=r[1];                        
                    }                    
                    $scope.rollnumber=$scope.idAgent;
                    $scope.empname=angular.uppercase(employee.Fname+" "+employee.Lname);
                    $scope.empsexe=employee.sexe;
                    $scope.empphone=employee.phone;
                    $scope.empemail=employee.email;
                    $scope.empbirth=employee.birthday;
                    $scope.empmaretal=employee.maretalStatus;
                    $scope.Level=employee.levelGrade;
                    $scope.services=employee.servicesWork;
                    $scope.totalSalary=employee.salary;					
					$scope.familly=famill;
					$scope.nbenfant=famill.length;
				}
                else{                    
                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
                }
            });
        }else{
            $scope.$emit('alert',{msg:"enter a number"});
        }
    };
    if($routeParams.id){
        if(Number($routeParams.id)){
            $scope.idAgent=Number($routeParams.id);
            $scope.searchAgent();
        }        
    }
   
});
app.controller("congigCtrl",function($scope,initApp){
    $scope.$on("yesConfirm",function(e,data){
        var func=data.func;
        var id=data.id;
        if(func!=null){
            if(id!=null){
                $scope[func](id);
            }else{
                $scope[func]();
            }
        }
    });
    $scope.myconfig="departement";
    $scope.editDepartment=false;
    $scope.$emit("config");
    $scope.getDepart=function(){
        initApp.getDepartment(function(r){
            if(angular.isObject(r) && !angular.isUndefined(r) && r.status==200){
                $scope.departement= JSON.parse(r.response);
            }else{                
                $scope.$emit('danger',{msg:(r.message?r.message:r)});
            }
        });	
    };
    $scope.getDepart();
    $scope.displayServices=function(){
        initApp.getServices("services",function(r){
            if (angular.isObject(r) && !angular.isUndefined(r) && r.status == 200) {
                $scope.services= JSON.parse(r.response);
            }else{                
                $scope.$emit('danger',{msg:(r.message?r.message:r)});
            }
        });	
    };
    $scope.displayServices();
    $scope.addDepartement=function(){
        var AddDepartName=$("AddDepartName").value;
        if(!angular.isUndefined(AddDepartName) && AddDepartName!=""){
            initApp.addDepartement(AddDepartName,function(r){
                if(r=="success depart"){
                    $scope.$emit('succes',{msg:"Departement add successfully"});
                    $scope.AddDepartName=null;
                    $scope.getDepart();
                }else{                    
                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
                }       
            }); 
        }else{
            $scope.$emit('alert',{msg:"Enter a departement"});
        }
    };
    $scope.deleteDep=function(id){        
        var data={
            msg:"would like to delete these Departement?it will delete alse services related to it",
            operation:"remouveDep",
            id:id
        };
        $scope.$emit('confirm',data);
    };
    $scope.remouveDep=function(id){
        $scope.$emit('alert',{msg:id});
        initApp.deleteDepartment(id,function(r){
            if(r=="delete_deport success"){
                $scope.getDepart();
                $scope.$emit('succes',{msg:"Department Delete Succesfully"});
            }else{
                $scope.$emit('danger',{msg:(r.message?r.message:r)});
            }
        });
    };
    $scope.addServices=function(){
        var id=$("servidepart").value;
        var name =$("servName").value;
        if((!angular.isUndefined(id) && Number(id)) && (!angular.isUndefined(name) && name!="")){
            var serv={
                idDepart:id,
                servName:$("servName").value
            }            
            initApp.addServices(serv,function(r){
                if(r=="success revices"){
                    $scope.displayServices();                    
                    $scope.$emit('succes',{msg:"Service add Succesfully"});
                }else{                    
                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
                }
            });
        }else{            
            $scope.$emit('alert',{msg:"You cant Empty services or Services to empty Departement"});
        }
    };
    $scope.deleteServices=function(id){
        if(confirm("would like to delete these Services?")){
            initApp.deleteDepartment(id,function(r){
                if(r=="delete_deport success"){
                    $scope.displayServices();                    
                    $scope.$emit('succes',{msg:"Department Delete Succesfully"});
                }else{                    
                    $scope.$emit('danger',{msg:(r.message?r.message:r)});
                }
            });
        }
    };
    $scope.getConfigSalary=function(){
        initApp.getConfigSalary(function(r){
            if (angular.isObject(r) && !angular.isUndefined(r) && r.status == 200){
                $scope.confSal=JSON.parse(r.response);
            }else{
                $scope.$emit('danger',{msg:r});
            }
        });
    };
    $scope.getConfigSalary();
});
function $(id){return document.getElementById(id);}