var angRoute  = '';
if(window.location.hostname=='localhost')
{
	var angRoute = "http://localhost/reworx";
}
else if(window.location.hostname=='demo.onsisdev.info')
{
	var angRoute = "http://demo.onsisdev.info/tracking";
}
var trackingApp = angular.module('trackingApp',['ngRoute','ngSanitize']);
trackingApp.config(['$routeProvider','$controllerProvider','$locationProvider','$httpProvider',function($routeProvider,$controllerProvider,$locationProvider,$httpProvider){
	trackingApp.registerCtrl = $controllerProvider.register;
	
	$routeProvider
	
	.when('/',{'templateUrl':angRoute+'/page/home.php'
		})
	
	.when('/login',{'templateUrl':function(params){ return angRoute+'/page/home.php'}
		})	
		
	.when('/uploadcsv',{'templateUrl':angRoute+'/page/uploadcsv.php'
		})
		
	.when('/search/',{'templateUrl':function(params){ return angRoute+'/page/search.php'}
		})
		
	.when('/report/',{'templateUrl':function(params){ return angRoute+'/page/report.php'}
		})
		
	.when('/user-report/',{'templateUrl':function(params){ return angRoute+'/page/report.php'}
		})			
		
	.when('/refer/:UID',{'templateUrl':function(params){ return angRoute+'/page/refer.php?UID='+params.UID}
		})
			
	.when('/request/',{'templateUrl':function(params){ return angRoute+'/page/request.php'}
		})
		
	.when('/tutorial/',{'templateUrl':function(params){ return angRoute+'/page/tutorial.php'}
		})
		
	.when('/employee-dashboard/',{'templateUrl':function(params){ return angRoute+'/page/employee-dashboard.php'}
		})
		

     .otherwise({redirectTo:angRoute+'/'});	
	
	 $locationProvider.html5Mode(true);
	 $locationProvider.hashPrefix('!');
	
	}
]);
trackingApp.run(['$routeParams','$rootScope','$timeout','$location','$templateCache','$http', function($routeParams,$root,$timeout,$location,$templateCache,$http) 
{
	
	$root.$on('$routeChangeSuccess', function(e, current, pre) 
	 {
		$root.currentUrl = current.originalPath;
    });
    
    $root.logout = function() 
    {
	    var absUrl = angRoute+'/api/logout.php';
		$http.post(absUrl,).success(function(response)
		{
			window.location.href =  angRoute;
		});
    };
    
    $root.togglePopup = function(tmp)
	{
	    $root.errorLoginMsg = '';
	    $('#myModal').modal(tmp);
	    
	}
	$root.redirectPage = function()
	{
		window.location.href =  angRoute+'/';
	}
	$root.showLoderlogin = false;
	$root.userarr = {};
	$root.divHeight = function(){
		var minh=$(window).height() - ($("header").outerHeight()+87);
		return minh+'px';
		};
	$root.userLogin = function()
	{
		$root.errorLoginMsg = '';
		if($root.userarr.email && $root.userarr.password)
		{
			
			$root.showLoderlogin = true;
			var absUrl = angRoute+'/api/auth.php';
			$http.post(absUrl,{data:$root.userarr}).success(function(response)
			{
				if(response.status=='success')
				{
					window.location.href =  angRoute+'/search';
				}
				else if(response.status=='csvnotuploaded')
				{
					window.location.href =  angRoute+'/uploadcsv';
				}
				else if(response.status=='csvuploaded')
				{
					window.location.href =  angRoute+'/employee-dashboard';
				}
				else
				{
					$root.errorLoginMsg = response.msg;
				}
			  $root.showLoderlogin = false;	
			})
		}
		else
		{
			if(!$root.userarr.email)
			{
				$root.errorLoginMsg = 'Please enter valid email id';
			}
			else if(!$root.userarr.password)
			{
				$root.errorLoginMsg = 'Please enter password';
			}
			return false;
		}
	}	
  
}]);
trackingApp.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});

trackingApp.directive('fileModel', ['$parse', function ($parse) {
	return {
	   restrict: 'A',
	   link: function(scope, element, attrs) {
		  var model = $parse(attrs.fileModel);
		  var modelSetter = model.assign;
		  
		  element.bind('change', function(){
			 scope.$apply(function(){
				modelSetter(scope, element[0].files[0]);
			 });
		  });
	   }
	};
}]);

trackingApp.filter('to_trusted', ['$sce', function($sce) {
      return function(text) {
        return $sce.trustAsHtml(text);
      };
    }]);

