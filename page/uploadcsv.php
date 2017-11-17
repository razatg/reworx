<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="homeController">
<header>
	<div class="grid-center">
		<?php include_once('navbar.php');?>
	</div>
</header>	
<div class="banner-container">
	<h1><span class="light_green">Get Rewards,</span><span class="green"> by referring your Contacts and Friends.</span><br>
	Upload your LinkedIn Contacts to get started</h1>
	<div ng-if="!showLoder" class="linkden_container">
		<img src="newui/images/linkdenlogo.jpg" alt="">
		<h1>Upload your LinkedIn CSV </h1>
		<?php if(!empty($_SESSION['member']['email'])){?>
		<input class="application-btn" type="file" onchange="angular.element(this).scope().setFile(this)">
		<?php } 
		else
		 {?>
		  <a class="application-btn" ng-click="togglePopup('show')">Upload</a>
		<?php }?>
		<a class="more_btn">Learn how..</a>
	</div>
	<div ng-if="showLoder"><img src="newui/images/fancybox_loading.gif" alt=""></div>
	<h2>You will be providing publically accessible information Only.<br>
	 We will never contact anyone on your behalf</h2>
</div>
<?php include_once('login.php');?>
</div>
<script>
trackingApp.registerCtrl('homeController',function($scope,$http, $location, $timeout, $element)
{
	   $scope.showLoder = false;
	   $scope.setFile = function(element) 
	   {
	    $scope.showLoder = true;
        $scope.$apply(function($scope) 
        {
			$scope.files = element.files[0];
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/csvupload.php';
			var fd = new FormData()
			fd.append("imageName", element.files[0]);
			
			$http.post(absUrl, fd, 
			{
				transformRequest: angular.identity,
				headers: {
				'Content-Type': undefined
				}
			})
			.success(function(response) 
			{
				 $scope.showLoder = false;
				if(response.status=='success')
				{
					window.location.href =  angRoute+'/employee-dashboard';
				}
				else
				{
					window.location.href =  angRoute;
				}

			});
        });
    };
    
    $scope.togglePopup = function(tmp)
    {
		$('#myModal').modal(tmp);
	}

});	
</script>

