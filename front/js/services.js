app.service("initApp",function($http,$window,$location){
    let link="http://localhost/payroll_ulk/api";
    let usersLink=`${link}/users`;
    let departemantLink = `${link}/departements`;
    this.initilize=function(){
        var url="/login";
        if($window.sessionStorage.userPseudo && $window.sessionStorage.userEmail){
			url="";
		}
		$location.path(url);		
    };
    this.connection=function(user,cb){        
        var param="connect=getConnection&username="+user.userName+"&password="+user.passWord;
        $http({
            method:"POST",
            url: `${usersLink}/login`,
            data:param,
            headers:{'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function(response){
            var res=response.data[0];
            var token=response.data[1].token;
            if(angular.isObject(res) && !angular.isUndefined(res)) {         
                $window.sessionStorage.setItem("userId",res.idemployee);
                $window.sessionStorage.setItem("userFName",res.Fname);
                $window.sessionStorage.setItem("userLName",res.Lname);
                $window.sessionStorage.setItem("userType",res.idtypeAccess);
                $window.sessionStorage.setItem("userPseudo",res.username);
                $window.sessionStorage.setItem("userEmail",res.email);
                $window.sessionStorage.setItem("userBithday",res.birthday);
                $window.sessionStorage.setItem("userPhone",res.phone);
                $window.sessionStorage.setItem("userSexe",res.sexe);
                $window.sessionStorage.setItem("maretalSatus",res.maretalStatus);
                $window.sessionStorage.setItem("token",token);
                $window.sessionStorage.setItem("menu",true);
                cb("connect");
            }else{
                cb(res);
            }
        },errorServer)
    };
    this.gestAgents=function(cb){
        $http({
            method:"GET",
            url:usersLink ,
            headers:{'token':$window.sessionStorage.token}         
        }).then(function(response){
            cb(response.data);
        },errorServer);
    };    
    this.searchAgent=function(num,cb){
        $http({
            method:"GET",
            url:link+"user.php",
            headers:{'token':$window.sessionStorage.token}          
        }).then(function(response){
            cb(response.data);
        },errorServer);
    };
    this.getDetaillAgent=function(id,cb){
        $http({
            method:"GET",
            url: `${usersLink}/${id}/detail`,
            headers:{'token':$window.sessionStorage.token} 
        }).then(function(response){            
            cb(response.data); 
        },errorServer);
    };
    // 
    this.addDepartement=function(departName,cb){
        var param="config=addDepartement&Namedepart="+departName;
        $http({
            method:"POST",
            url:departemantLink+"myconfig.php",
            data:param,
            headers:{'Content-Type':'application/x-www-form-urlencoded',
                    'token':$window.sessionStorage.token
            }
        }).then(function(response){
            cb(response.data); 
        },errorServer)
    };
    this.addServices=function(serv,cb){
        var param="config=addServices&idDepart="+serv.idDepart+"&NameService="+serv.servName;
        $http({
            method:"POST",
            url:link+"myconfig.php",
            data:param,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'token': $window.sessionStorage.token
            }
        }).then(function(response){
            cb(response.data); 
        },errorServer)
    };
    this.getDepartment=function(cb){
        $http({
            method:"GET",
            url:departemantLink,
            headers: {
                'token': $window.sessionStorage.token
            }
        }).then(function(response){
            cb(response.data); 
        },errorServer)
    };
    this.getGrade=function(cb){
        $http({
            method:"GET",
            url:link+"myconfig.php",
            params:{                
                config:"getGrade"
            }            
        }).then(function(response){            
            cb(response.data); 
        },errorServer)
    };
    this.deleteDepartment=function(idServ,cb){
        $http({
            method:"GET",
            url:link+"myconfig.php",
            params:{                
                config:"deleteDepartment",
                idDepart:idServ
            }            
        }).then(function(response){
            cb(response.data); 
        },errorServer)
    };
    this.deleteServices=function(idServ,cb){
        $http({
            method:"GET",
            url:link+"myconfig.php",
            params:{                
                config:"deleteServices",
                idServices:idServ
            }            
        }).then(function(response){
            cb(response.data); 
        },errorServer);
    };
    this.getServices=function(src,cb){
        $http({
            method:"GET",
            url:`${departemantLink}/services`,
            headers: {
                'token': $window.sessionStorage.token
            }
        }).then(function(response){
            cb(response.data); 
        },errorServer);
    };
    this.getConfigSalary=function(cb){
        $http({
            method:"GET",
            url: `${departemantLink}/salary`,
            headers: {
                'token': $window.sessionStorage.token
            }
        }).then(function(response){
            cb(response.data); 
        },errorServer);
    };
 
});

var errorServer=function(response){
    console.log("Problem connection on server::"+response);
};